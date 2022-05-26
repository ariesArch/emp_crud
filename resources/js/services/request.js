import axios from "axios";
const setHeader = (jwt) => ({
    headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${jwt}`
    }
})
const Login = async (url, payload) => (await axios.post(url, payload)).data
const Logout = async (URL, jwt) => (await axios.get(URL, setHeader(jwt))).data
const postData = async (url, payload, jwt) => (await axios.post(url, payload, setHeader(jwt))).data
const putData = async (url, payload, jwt) => (await axios.put(url, payload, setHeader(jwt))).data
const getData = async (url, jwt) => (await axios.get(url, setHeader(jwt))).data
const deleteData = async (URL, jwt) => (await axios.delete(URL, setHeader(jwt))).data
export default {
    Login,
    Logout,
    postData,
    putData,
    getData,
    deleteData
}