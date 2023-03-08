<template>
  <div class="container">
    <h1>Tabulka</h1>

    <div
      class="row finalRanks"
      v-for="season in finalRanks"
      :key="season.id"
      v-show="season.value === activeSeason"
    >
      <div class="column" v-for="(team, index) in season.data" :key="team.id">
        <template v-if="index < 3">
          <img :src="`../images/logos/teams/${team.image_path}`" alt="" />
          <font-awesome-icon :icon="['fas', 'trophy']" class="fa-2x" />
        </template>
        <template v-else>
          <img :src="`../images/logos/teams/${team.image_path}`" alt="" />
          <span>{{ index + 1 }}</span>
        </template>
      </div>
    </div>

    <div class="buttonsWrapper">
      <button
        class="btn btn-lg btn-primary mr-md-3"
        v-for="season in seasons"
        :key="season.id"
        @click="activeSeason = season.id"
        :class="{ activeButton: activeSeason === season.id }"
      >
        {{ season.year }}
      </button>
    </div>
    <div class="row">
      <table class="col-sm-12 col-lg-3 col-lg-4 table table-results">
        <thead>
          <tr>
            <th class="divider"></th>
            <th>Základní část</th>
            <th class="ml-auto">W</th>
            <th>L</th>
            <th>PCT</th>
          </tr>
        </thead>
        <tbody
          v-for="season in teamStats"
          :key="season.id"
          v-show="season.value === activeSeason"
        >
          <tr v-for="team in season.data" :key="team.id">
            <td class="divider">{{ team.rank }}.</td>
            <td>{{ team.name }}</td>
            <td class="ml-auto">{{ team.winCount }}</td>
            <td>{{ team.lossCount }}</td>
            <td>{{ team.pct }}</td>
          </tr>
        </tbody>
        <div
          class="HR_MVP"
          v-for="season in seasons"
          :key="season.id"
          v-show="activeSeason === season.id"
        >
          <div class="HR_MVP__wrapper">
            <div class="HR_MVP__wrapper__img">
              <font-awesome-icon :icon="['fas', 'star']" class="plus fa-2x" />
              <span>MVP</span>
            </div>
            <span v-if="season.MVP">{{ season.MVP }}</span>
            <span v-else> bude doplněn</span>
          </div>
          <div class="HR_MVP__wrapper">
            <div class="HR_MVP__wrapper__img">
              <img src="../../public/images/baseball-bat.svg" />
              <span> HR Master</span>
            </div>
            <span v-if="season.HR_master">{{ season.HR_master }}</span>
            <span v-else> bude doplněn</span>
          </div>
        </div>
      </table>

      <table class="col-sm-12 col-lg-3 col-lg-4 table table-results">
        <thead>
          <tr>
            <th class="divider"></th>
            <th>Top HR's</th>
            <th class="ml-auto">HR</th>
          </tr>
        </thead>

        <tbody
          v-for="hrStatSeason in hrStats"
          :key="hrStatSeason.id"
          v-show="hrStatSeason.value === activeSeason"
        >
          <tr v-for="player in hrStatSeason.data.slice(0, 5)" :key="player.id">
            <td class="divider">{{ player.rank }}.</td>
            <td>{{ player.name }}</td>
            <td class="ml-auto">{{ player.hr_count }}</td>
          </tr>
          <a href="#tabulka" :class="{ bottom: hrStatSeason.shown }">
            <font-awesome-icon
              :icon="['fas', 'angles-down']"
              class="fa-2x"
              @click="hrStatSeason.shown = !hrStatSeason.shown"
              :class="{ rotate: hrStatSeason.shown }"
              v-show="hrStatSeason.data.length > 5 && hrStatSeason.shown"
            />
          </a>
          <a href="#" :class="{ bottom: hrStatSeason.shown }">
            <font-awesome-icon
              :icon="['fas', 'angles-down']"
              class="fa-2x"
              @click="hrStatSeason.shown = !hrStatSeason.shown"
              :class="{ rotate: hrStatSeason.shown }"
              v-show="hrStatSeason.data.length > 5 && !hrStatSeason.shown"
            />
          </a>
          <tr
            v-show="hrStatSeason.shown"
            v-for="player in hrStatSeason.data.slice(
              5,
              hrStatSeason.data.length
            )"
            :key="player.id"
          >
            <td class="divider">{{ player.rank }}.</td>
            <td>{{ player.name }}</td>
            <td class="ml-auto">{{ player.hr_count }}</td>
          </tr>
        </tbody>
      </table>
      <table class="col-sm-12 col-lg-3 col-lg-4 table table-results">
        <thead>
          <tr>
            <th class="divider"></th>
            <th>Top R's</th>
            <th class="ml-auto">R</th>
          </tr>
        </thead>
        <tbody
          v-for="runStatSeason in runStats"
          :key="runStatSeason.id"
          v-show="runStatSeason.value === activeSeason"
        >
          <tr v-for="player in runStatSeason.data.slice(0, 5)" :key="player.id">
            <td class="divider">{{ player.rank }}.</td>
            <td>{{ player.name }}</td>
            <td class="ml-auto">{{ player.run_count }}</td>
          </tr>
          <a href="#tabulka" :class="{ bottom: runStatSeason.shown }">
            <font-awesome-icon
              :icon="['fas', 'angles-down']"
              class="fa-2x"
              @click="runStatSeason.shown = !runStatSeason.shown"
              :class="{ rotate: runStatSeason.shown }"
              v-show="runStatSeason.data.length > 5 && runStatSeason.shown"
            />
          </a>
          <a href="#" :class="{ bottom: runStatSeason.shown }">
            <font-awesome-icon
              :icon="['fas', 'angles-down']"
              class="fa-2x"
              @click="runStatSeason.shown = !runStatSeason.shown"
              :class="{ rotate: runStatSeason.shown }"
              v-show="runStatSeason.data.length > 5 && !runStatSeason.shown"
            />
          </a>
          <tr
            v-show="runStatSeason.shown"
            v-for="player in runStatSeason.data.slice(
              5,
              runStatSeason.data.length
            )"
            :key="player.id"
          >
            <td class="divider">{{ player.rank }}.</td>
            <td>{{ player.name }}</td>
            <td class="ml-auto">{{ player.run_count }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from "../api";

export default {
  components: {},

  data() {
    return {
      hrStats: [],
      runStats: [],
      stats: [],
      activeSeason: 0,
      seasons: [],
      teamStats: [],
      teamsSeasonly: [],
      finalRanks: [],
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
              this.activeSeason = season.id;
              return;
            }
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },

    async getGames() {
      try {
        let data = await axios.get("/api/games");

        let notFinalGames = [];
        this.groupByKey(data.data, "isFinal").filter((item) => {
          if (item.value === 0) {
            notFinalGames.push(item.data);
          }
        });
        this.teamStats = this.countGames(notFinalGames);

        this.teamStats = this.groupByKey(this.teamStats, "season_id");
        this.teamStats.forEach((season) => {
          this.makeIndexes(this.sortByKey(season.data, "winCount"), "pct");
        });
        this.teamStats.forEach((season) => {
          season.data.filter((item) => {
            if (item.pct == "NaN") {
              item.pct = "0.000";
            }
          });
          if (season.data[0].numberOfGames === 0) {
            this.sortByKeyString(season.data, "name");
            season.data.forEach((team, index) => {
              team.pct = "0.000";
              team.rank = index + 1;
            });
          }
        });
      } catch (error) {
        console.log(error);
      }
    },

    removeElementsWithZero(array) {
      let newArray = [];
      array.forEach((element) => {
        if (element.hr_count != 0) {
          newArray.push(element);
        }
      });
      return newArray;
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
            shown: false,
          };
        } else {
          last.data.push(item);
        }
      }
      if (last.data !== null && last.value !== null) result.push(last);

      return result;
    },

    sortByKey(array, key) {
      return array.sort((b, a) => {
        let x = a[key];
        let y = b[key];
        x = parseInt(x);
        y = parseInt(y);
        return x - y || b["lossCount"] - a["lossCount"];
      });
    },

    sortByKeyString(array, key) {
      return array.sort((a, b) => {
        let x = a[key];
        let y = b[key];
        return x < y ? -1 : x > y ? 1 : 0;
      });
    },

    makeIndexes(array, key) {
      let lastItem = (array[0].rank = 1);
      array.forEach((element, index) => {
        if (element[key] === lastItem[key]) {
          element.rank = lastItem.rank;
        } else {
          element.rank = index + 1;
        }
        lastItem = element;
      });
      return array;
    },

    async getTeamsBySeason() {
      try {
        const response = await axios.get("/api/teams/seasonly");
        this.teamsSeasonly = response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getFinalRanks() {
      try {
        const response = await axios.get("/api/finalRanks");
        this.finalRanks = response.data;
        this.finalRanks = this.groupByKey(this.finalRanks, "season_id");
      } catch (error) {
        console.log(error);
      }
    },

    countGames(array) {
      this.teamsSeasonly.forEach((team) => {
        (team.winCount = 0), (team.lossCount = 0), (team.numberOfGames = 0);
      });
      let teams = this.teamsSeasonly;

      array.forEach((groupOfGames) => {
        groupOfGames.forEach((game) => {
          teams.filter((team) => {
            if (game.season_id === team.season_id) {
              if (
                game.team_home_score > game.team_away_score &&
                game.team_home_id === team.team_id
              ) {
                team.winCount++;
                team.numberOfGames++;
              } else if (
                game.team_home_score > game.team_away_score &&
                game.team_away_id === team.team_id
              ) {
                team.lossCount++;
                team.numberOfGames++;
              } else if (
                game.team_home_score < game.team_away_score &&
                game.team_home_id === team.team_id
              ) {
                team.lossCount++;
                team.numberOfGames++;
              } else if (
                game.team_home_score < game.team_away_score &&
                game.team_away_id === team.team_id
              ) {
                team.winCount++;
                team.numberOfGames++;
              }
            }
          });
        });
      });
      teams.forEach((team) => {
        team.pct = (team.winCount / team.numberOfGames).toFixed(3);
      });
      return teams;
    },

    getStats() {
      axios
        .get("/api/statsCounts")
        .then((response) => {
          response.data.forEach((player) => {
            this.hrStats.push({
              name: player.name,
              hr_count: player.hr_count,
              season_id: player.season_id,
            });
            this.runStats.push({
              name: player.name,
              run_count: player.run_count,
              season_id: player.season_id,
            });
          });

          this.hrStats = this.groupByKey(
            this.removeElementsWithZero(this.hrStats),
            "season_id"
          );

          this.hrStats.forEach((element) => {
            this.sortByKey(element.data, "hr_count");
            this.makeIndexes(element.data, "hr_count");
          });

          this.runStats = this.groupByKey(this.runStats, "season_id");
          this.runStats.forEach((element) => {
            this.makeIndexes(element.data, "run_count");
          });
          /*  this.teamStats.forEach((season) => {
            this.hrStats.filter((item) => {
              if (item.value === season.value) {
                item.countOfTeams = season.data.length;
              }
            });

            this.runStats.filter((item) => {
              if (item.value === season.value) {
                item.countOfTeams = season.data.length;
              }
            });
          });*/
        })

        .catch((error) => {
          console.log(error);
        });
    },
  },
  created() {
    this.getSeasons();
    this.getTeamsBySeason().then(this.getGames);
    this.getStats();
    this.getFinalRanks();
  },
};
</script>
<style lang="scss" scoped>
table {
  margin-bottom: 2.6rem;
  height: auto;
  display: flex;
  flex-direction: column;
  padding: 0;
  color: white;
  tbody {
    position: relative;
  }
  thead {
    width: 100%;

    & > tr {
      border-bottom: 4px solid #dee2e6;
    }
  }
  tr {
    display: flex;
    flex-wrap: nowrap;

    .ml-auto {
      margin-left: auto;
    }
    th:nth-last-child(1),
    td:nth-last-child(1) {
      width: 15%;
    }

    .divider {
      padding-right: 0.1rem;
    }
  }
}
.row {
  position: relative;

  a {
    position: absolute;
    left: calc(50% - 10px);
    width: 20px;
    &.bottom {
      bottom: -2rem;
    }
    svg.rotate {
      transform: rotate(180deg);
    }
  }
}
.buttonsWrapper {
  margin-bottom: 1rem;
  button {
    color: white !important;
    background: #12284b !important;
    border: 1px solid white;

    &.activeButton {
      color: #12284b !important;
      background: #ffffff !important;
    }
  }
}
.HR_MVP {
  margin-top: 2rem;
  & > div {
    margin-bottom: 0.6rem;
  }
  img {
    height: 1.9rem;
    width: 1.9rem;
  }
  &__wrapper {
    display: flex;
    align-items: center;

    &__img {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 5rem;
    }
    & > span {
      margin-left: 0.8rem;
      font-size: 1.2rem;
    }
  }
}
</style>