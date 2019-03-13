import axios from "axios";
import store from "@/store";

// Request interceptor
axios.interceptors.request.use(request => {
    const token = store.getters["auth/token"];
    if (token) {
        request.headers.common["Authorization"] = `Bearer ${token}`;
    }
    return request;
});

// Response interceptor
axios.interceptors.response.use(
    response => response,
    error => {
        const { status } = error.response;

        if (status >= 500) {
            console.error("error 500");
        }

        if (status === 401 && store.getters["auth/check"]) {
            console.error("error 401");
        }

        return Promise.reject(error);
    }
);
