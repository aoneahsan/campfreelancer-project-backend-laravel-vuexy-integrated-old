// axios
import axios from 'axios'

const baseURL = "http://localhost:8000";

axios.create({
    baseURL
    // You can add your headers here
});

axios.defaults.withCredentials = true;

export default axios
