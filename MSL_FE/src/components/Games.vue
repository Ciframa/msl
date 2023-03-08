<template>
  <div class="container">
    <div class="row">
      <div class="col-xl-6">
        <h1>Zápasy</h1>
        <div class="buttonsWrapper">
          <button
            class="btn btn-lg btn-primary mr-md-3"
            v-for="season in seasons"
            :key="season.id"
            @click="activeYear = season.id"
            :class="{ activeButton: activeYear === season.id }"
          >
            {{ season.year }}
          </button>
        </div>
        <template v-if="leaugeData[activeYear - 1]">
          <div
            class="matches"
            v-for="years in leaugeData"
            :key="years.id"
            v-show="activeYear === years.value"
          >
            <template v-for="(gameDate, index) in years.data">
              <h2 :key="gameDate.id">
                {{ index + 1 }}. kolo {{ gameDate.data[0].day }}
                {{ gameDate.value }}
              </h2>
              <div
                class="match_wrapper"
                v-for="game in gameDate.data"
                :key="game.id"
              >
                <div class="match">
                  <span class="team1">
                    <img
                      v-if="game.homeTeamImage !== null"
                      :src="`../images/logos/teams/${game.homeTeamImage}`"
                    />
                    <span v-else>?</span>
                  </span>
                  <span class="team1-result">{{ game.teamHomeScore }} </span>
                  <span class="delimiter"> : </span>
                  <span class="team2-result">{{ game.teamAwayScore }} </span>
                  <span class="team2">
                    <img
                      v-if="game.homeTeamImage !== null"
                      :src="`../images/logos/teams/${game.awayTeamImage}`"
                    />
                    <span v-else>?</span>
                  </span>
                </div>
                <div class="match_info">
                  <div class="time">
                    <font-awesome-icon :icon="['fas', 'clock']" />
                    <span>{{ game.time }}</span>
                  </div>
                  <div class="referee">
                    <img src="../../public/images/whistle.svg" />
                    <span v-if="game.referee">{{ game.referee }}</span>
                    <span v-else>Rozhodčí bude upřesněn</span>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </template>
        <template v-else>
          <h2>
            Sezonní turnaj pro rok {{ this.seasonIdToYear() }} bude upřesněn!
          </h2>
        </template>
      </div>
      <div class="col-xl-6" id="final">
        <h1>Finálový turnaj</h1>
        <template v-if="finalData[activeYear - 1]">
          <div
            class="matches"
            v-for="gameDate in finalData"
            v-show="activeYear === gameDate.value"
            :key="gameDate.id"
          >
            <h2>
              {{ gameDate.data[0].day }}
              {{ gameDate.data[0].date }}
            </h2>
            <template v-for="game in gameDate.data">
              <div class="match_wrapper" :key="game.id">
                <div class="match">
                  <span class="team1">
                    <img
                      v-if="game.homeTeamImage !== null"
                      :src="`../images/logos/teams/${game.homeTeamImage}`"
                    />
                    <span v-else>?</span>
                  </span>
                  <span class="team1-result">{{ game.teamHomeScore }} </span>
                  <span class="delimiter"> : </span>
                  <span class="team2-result">{{ game.teamAwayScore }} </span>
                  <span class="team2">
                    <img
                      v-if="game.homeTeamImage !== null"
                      :src="`../images/logos/teams/${game.awayTeamImage}`"
                    />
                    <span v-else>?</span>
                  </span>
                </div>
                <div class="match_info">
                  <div class="time">
                    <font-awesome-icon :icon="['fas', 'clock']" />
                    <span>{{ game.time }}</span>
                  </div>
                  <div class="referee">
                    <img src="../../public/images/whistle.svg" />
                    <span v-if="game.referee">{{ game.referee }}</span>
                    <span v-else>Rozhodčí bude upřesněn</span>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </template>
        <template v-else>
          <h2>
            Finálový turnaj pro rok {{ this.seasonIdToYear() }} bude upřesněn!
          </h2>
        </template>
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
      games: [],
      seasons: [],
      activeYear: 0,
    };
  },
  methods: {
    getSeasons() {
      axios
        .get("/api/seasons")
        .then((response) => {
          this.seasons = response.data;
          this.seasons.forEach((season) => {
            if (season.isActual) {
              this.activeYear = season.id;
              return;
            }
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },

    separateGames(array) {
      let finalGames = [];
      let leaugeGames = [];
      array.forEach((item) => {
        item.isFinal === 0 ? leaugeGames.push(item) : finalGames.push(item);
      });
      return { final: finalGames, leauge: leaugeGames };
    },
    groupByKey(arr, key) {
      let result = [];
      let last = {
        value: null,
        data: null,
      };

      for (let item of arr) {
        if (last.value !== item[key]) {
          if (last.data !== null && last.value !== null) result.push(last);

          last = {
            value: item[key],
            data: [item],
          };
        } else {
          last.data.push(item);
        }
      }
      if (last.data !== null && last.value !== null) result.push(last);

      return result;
    },

    getGames() {
      axios
        .get("/api/games")
        .then((response) => {
          this.games = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    indexToDay(index) {
      let days = [
        "Neděle",
        "Pondělí",
        "Úterý",
        "Středa",
        "Čtvrtek",
        "Pátek",
        "Sobota",
      ];
      return days[index];
    },
    seasonIdToYear() {
      return this.seasons.find((season) => season.id === this.activeYear)?.year;
    },
  },
  created() {
    this.getGames();
    this.getSeasons();
  },
  computed: {
    gamesData: function () {
      return this.games.map((game) => {
        return {
          gameId: game.id,
          date: new Date(game.time.split(" ")[0]).toLocaleDateString(),
          day: this.indexToDay(new Date(game.time).getDay()),
          time: game.time.split(" ")[1].toString().substring(0, 5),
          teamHomeScore: game.team_home_score.toString().padStart(2, 0),
          teamAwayScore: game.team_away_score.toString().padStart(2, 0),
          teamHome: game.team_home_name,
          teamAway: game.team_away_name,
          isActual: game.isActual,
          isFinal: game.isFinal,
          seasonYear: game.year,
          seasonId: game.season_id,
          homeTeamImage: game.image_path_home,
          awayTeamImage: game.image_path_away,
          referee: game.referee_name,
          refereeId: game.referee,
        };
      });
    },

    leaugeData: function () {
      let groupedByYear = this.groupByKey(
        this.separateGames(this.gamesData).leauge,
        "seasonId"
      );
      let groupedByYearAndDate = [];
      for (let group of groupedByYear) {
        groupedByYearAndDate.push({
          value: group.value,
          data: this.groupByKey(group.data, "date"),
        });
      }

      return groupedByYearAndDate;
    },
    finalData: function () {
      return this.groupByKey(
        this.separateGames(this.gamesData).final,
        "seasonId"
      );
    },
  },
};
</script>
<style lang="scss" scoped>
button {
  background: white !important;
  color: #12284b !important;
  box-shadow: none !important;
}
.activeButton {
  background: #12284b !important;
  color: white !important;
}
</style>