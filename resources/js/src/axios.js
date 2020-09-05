// axios
import axios from 'axios'

const baseURL = env('AXIOS_ROOT_URL');

console.log("baseURL = ",baseURL);

axios.create({
    baseURL
    // You can add your headers here
});

axios.defaults.withCredentials = true;

export default axios
