import axios from "axios";

// actions
export const actions = {
    async clientDetail({ dispatch }, id) {
        try {
            return axios.get("/api/clients/detailsClient/" + id);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    }
};
