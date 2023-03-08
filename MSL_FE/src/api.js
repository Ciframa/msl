import axios from "axios";

export default axios.create({
  baseURL: `http://127.0.0.1:8000`,
  headers: {
    "Content-Type": "application/json",
    Authorization: localStorage.getItem("token"),
  },
  xsrfCookieName: "csrftoken",
  xsrfHeaderName: "X-CSRFToken",
  withCredentials: true,
});
/*

export default axios.create({
  baseURL: `https://mslbe.fitmo.cz/`,
  headers: {
    "Content-Type": "application/json",
    Authorization: localStorage.getItem("token"),
  },
  xsrfCookieName: "csrftoken",
  xsrfHeaderName: "X-CSRFToken",
  withCredentials: true,
});
*/
