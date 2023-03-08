<template>
  <div class="team_wrapper" :class="teamIndexToName(activeTeam)">
    <div class="container">
      <h1>Týmy</h1>
      <div class="row">
        <div class="col-md-4 teamlist">
          <a
            v-for="(team, index) in finalPlayers"
            :key="index"
            href="#"
            :class="{ active: activeTeam === team.teamId }"
            @click="activeTeam = team.teamId"
          >
            <img
              :src="`../images/logos/teams_names/${team.teamImage}`"
              :alt="team.teamName"
            />
          </a>
        </div>

        <div class="col-md-8">
          <ul
            class="players"
            v-for="(team, i) in finalPlayers"
            :key="i"
            v-show="team.teamId === activeTeam"
          >
            <li v-for="(player, j) in team.teamPlayers" :key="j">
              <span class="number"
                >&#35;{{ player.number.toString().padStart(2, 0) }}</span
              >
              {{ player.player_name }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "../api";

export default {
  components: {},

  data() {
    return {
      players: [],
      activeTeams: [],
      activeTeam: 1,
    };
  },

  methods: {
    getPlayers() {
      axios
        .get("/api/players/active")
        .then((response) => {
          this.players = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getActiveTeams() {
      axios
        .get("/api/teams/active")
        .then((response) => {
          this.activeTeams = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    camelCase(str) {
      return str
        .replace(/\s(.)/g, function (a) {
          return a.toUpperCase();
        })
        .replace(/\s/g, "")
        .replace(/^(.)/, function (b) {
          return b.toLowerCase();
        });
    },
    teamIndexToName(teamId) {
      if (teamId === "null") return "Nezařazeno";
      return this.activeTeams
        .filter((item) => item.id == teamId)[0]
        ?.name.replace(/\s/g, "-");
    },
  },

  created() {
    this.getPlayers();
    this.getActiveTeams();
  },
  computed: {
    finalPlayers: function () {
      return this.activeTeams.map((team) => {
        return {
          teamId: team.id,
          teamName: team.name,
          teamImage: team.image_path,
          teamPlayers: this.players.filter(
            (player) => player.team_name === team.name
          ),
        };
      });
    },
  },
};
</script>
<style lang="scss">
</style>