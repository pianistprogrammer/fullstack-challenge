<template>
  <div>
    <template v-if="users.length > 0">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Latitude</th>
            <th>Longitude</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in users"
            :key="user.id"
            @click="showWeatherReport(user)"
          >
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.latitude }}</td>
            <td>{{ user.longitude }}</td>
          </tr>
        </tbody>
      </table>
    </template>
    <template v-else> Loading user data... </template>
    <modal
      :title="selectedUser ? selectedUser.name + '\'s Weather Report' : ''"
      v-model:is-open="isModalOpen"
      @update:is-open="closeModal"
    >
      <div v-if="selectedUser">
        <template v-if="selectedUser.weather">
          <p>
            <strong>Temperature</strong>:
            {{
              selectedUser.weather.current
                ? selectedUser.weather.current.temp
                : ""
            }} 'c
          </p>
          <p>
            <strong>Humidity</strong>:
            {{
              selectedUser.weather.current
                ? selectedUser.weather.current.humidity
                : ""
            }} %
          </p>
          <p>
            <strong>Wind Speed:</strong>
            {{
              selectedUser.weather.current
                ? selectedUser.weather.current.wind_speed
                : ""
            }} km/h
          </p>
        </template>
        <template v-else>
          <p>Loading weather report...</p>
        </template>
      </div>
    </modal>
  </div>
</template>

<script>
import modal from "@/components/WeatherModal.vue";
import axios from "axios";
const baseUrl = "http://127.0.0.1/api/v1";
export default {
  components: {
    modal,
  },
  data() {
    return {
      users: [],
      selectedUser: null,
      isModalOpen: false,
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get(`${baseUrl}/users`);
        this.users = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async fetchWeatherReport(user) {
      try {
        const response = await axios.get(
          `${baseUrl}/users/${user.latitude}/${user.longitude}`
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async showWeatherReport(user) {
      try {
        this.selectedUser = user;
        this.isModalOpen = true;
        const weatherReport = await this.fetchWeatherReport(user);
        this.selectedUser = {
          ...user,
          weather: weatherReport,
        };
      } catch (error) {
        console.log(error);
      }
    },
    closeModal() {
      this.selectedUser = null;
      this.isModalOpen = false;
    },
  },
};
</script>

<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  text-align: left;
  padding: 8px;
  border-bottom: 1px solid #ddd;
}

tr:hover {
  background-color: #f5f5f5;
  cursor: pointer;
}

.modal {
  background-color: transparent;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
}

.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 600px;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>
