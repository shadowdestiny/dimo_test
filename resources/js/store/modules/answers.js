import axios from "axios";

// actions
export const actions = {


    async loanDetail({ dispatch },id) {
        try {

            return axios.get("/api/answers/loanDetail/"+id);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },


};
