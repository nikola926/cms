import axios from "axios";

const API_URL = 'http://127.0.0.1:8000/api/auth/';

class AuthService {

    login(user) {

        return axios.post(`${API_URL}login`, {
            email: user.email,
            password: user.password,

        }).then(response => {
            console.log(response);
            if(response.data.access_token) {
                localStorage.setItem('user', JSON.stringify(response.data));
            }

            return response.data;
        })
    }

    logout() {
        localStorage.removeItem('user');
    }

    register(user) {
        return axios.post(`${API_URL}register`, {
            name: user.name,
            email: user.email,
            password: user.password
        });
    }

}

export default new AuthService();
