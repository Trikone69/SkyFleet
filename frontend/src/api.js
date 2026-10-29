import axios from 'axios';
const api=axios.create({baseURL:import.meta.env.VITE_API_URL||'http://localhost:8080/api/v1'});
api.interceptors.request.use(c=>{const token=localStorage.getItem('skyfleet_token');if(token)c.headers.Authorization=`Bearer ${token}`;return c;});
api.interceptors.response.use(r=>r,e=>{if(e.response?.status===401){localStorage.removeItem('skyfleet_token');localStorage.removeItem('skyfleet_user');if(location.pathname!=='/login')location.href='/login';}return Promise.reject(e);});
export default api;
