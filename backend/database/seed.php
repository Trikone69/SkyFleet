<?php
require dirname(__DIR__).'/vendor/autoload.php';
if(file_exists(dirname(__DIR__).'/.env')) Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
$required=static function(string $name): string {
    $value=getenv($name);
    if($value===false || $value==='') $value=$_ENV[$name]??'';
    if($value==='') throw new RuntimeException("Set {$name} before seeding the database.");
    return $value;
};
$host=getenv('DB_HOST')?:($_ENV['DB_HOST']??'127.0.0.1');
$port=getenv('DB_PORT')?:($_ENV['DB_PORT']??'3306');
$name=getenv('DB_NAME')?:($_ENV['DB_NAME']??'skyfleet');
$pdo=new PDO('mysql:host='.$host.';port='.$port.';dbname='.$name.';charset=utf8mb4',$required('DB_USER'),$required('DB_PASS'),[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$u=$pdo->prepare('INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name),password_hash=VALUES(password_hash)');
$u->execute(['SkyFleet Admin','admin@skyfleet.local',password_hash($required('SEED_ADMIN_PASSWORD'),PASSWORD_DEFAULT),'admin']);
$u->execute(['Pilot One','pilot@skyfleet.local',password_hash($required('SEED_PILOT_PASSWORD'),PASSWORD_DEFAULT),'pilot']);
$key=$required('DEMO_DRONE_API_KEY');
$d=$pdo->prepare('INSERT INTO drones(serial_no,name,model,api_key_hash) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name),api_key_hash=VALUES(api_key_hash)');$d->execute(['SF-001','Demo Drone','Falcon X1',hash('sha256',$key)]);$id=(int)$pdo->query("SELECT id FROM drones WHERE serial_no='SF-001'")->fetchColumn();$pilot=(int)$pdo->query("SELECT id FROM users WHERE email='pilot@skyfleet.local'")->fetchColumn();$a=$pdo->prepare('INSERT INTO assignments(drone_id,pilot_id) SELECT ?,? WHERE NOT EXISTS(SELECT 1 FROM assignments WHERE drone_id=? AND pilot_id=? AND unassigned_at IS NULL)');$a->execute([$id,$pilot,$id,$pilot]);$pdo->exec("INSERT INTO geofences(name,center_lat,center_lng,radius_m) SELECT 'Demo Airfield',37.7749,-122.4194,5000 WHERE NOT EXISTS(SELECT 1 FROM geofences WHERE name='Demo Airfield')");echo "Seeded demo data for admin@skyfleet.local and pilot@skyfleet.local.\n";
