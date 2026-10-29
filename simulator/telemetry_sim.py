#!/usr/bin/env python3
"""Send simulated readings to SkyFleet. Requires Python 3 and requests."""
import os, random, time, datetime, urllib.request, json, sys
API=os.getenv('SKYFLEET_API','http://localhost:8080/api/v1/telemetry')
KEY=os.getenv('DRONE_API_KEY')
if not KEY:
    sys.exit('Set DRONE_API_KEY to the key configured for the seeded drone.')
lat,lng=37.7749,-122.4194
battery=100
while True:
    lat+=random.uniform(-0.0003,0.0003); lng+=random.uniform(-0.0003,0.0003); battery=max(0,battery-random.choice([0,0,1]))
    payload={'battery_pct':battery,'latitude':round(lat,6),'longitude':round(lng,6),'altitude_m':round(random.uniform(40,120),2),'speed_ms':round(random.uniform(2,12),2),'recorded_at':datetime.datetime.now(datetime.timezone.utc).strftime('%Y-%m-%d %H:%M:%S')}
    req=urllib.request.Request(API,data=json.dumps(payload).encode(),headers={'Content-Type':'application/json','X-Drone-Api-Key':KEY},method='POST')
    try:
        with urllib.request.urlopen(req,timeout=10) as res: print(res.status, payload)
    except Exception as exc: print('Telemetry error:',exc)
    time.sleep(2)
