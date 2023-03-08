<template>
  <div v-if="Object.keys(tokenData).length === 0">
    <h1>Přihlaš se prosím</h1>
    <a href="/admin">Login</a>
  </div>
  <div class="adminPanelGames" v-else>
    <vue-confirm-dialog></vue-confirm-dialog>
    <div class="row container">
      <div class="col-xl-6">
        {{ logMessage }}
        <h1>Zápasy</h1>
        <div class="buttonsWrapper">
          <button
            class="btn btn-lg btn-primary mr-md-3"
            v-for="season in seasons"
            :key="season.id"
            @click="activeYear = season.year"
          >
            {{ season.year }}
          </button>
        </div>
        <font-awesome-icon
          :icon="['fas', 'circle-plus']"
          class="plus"
          @click="addGame((isFinal = 0))"
        />
        <font-awesome-icon
          :icon="['fas', 'circle-plus']"
          class="plus plus__final"
          @click="addGame((isFinal = 1))"
        />

        <div
          class="matches"
          v-for="years in leaugeData"
          :key="years.id"
          v-show="activeYear === years.value"
        >
          <template v-for="(gameDate, gameDateIndex) in years.data">
            <h2 :key="gameDate.id">
              {{ gameDateIndex + 1 }}. kolo {{ gameDate.data[0].day }}
              {{ gameDate.value }}
            </h2>
            <div
              class="match_wrapper"
              v-for="game in gameDate.data"
              :key="game.id"
            >
              <div class="match">
                <span class="team1">
                  <select v-model="game.teamHomeId">
                    <option
                      v-for="team in teamsSeasonly"
                      :key="team.id"
                      :selected="game.teamHomeId == team.team_id"
                      :value="team.team_id"
                      v-show="team.year === activeYear"
                    >
                      {{ team.name }}
                    </option>
                  </select>
                </span>
                <select v-model="game.teamHomeScore">
                  <option
                    v-for="number in 101"
                    :key="number + 'teamHomeScore'"
                    :value="(number - 1).toString().padStart(2, 0)"
                    :selected="
                      (number - 1).toString().padStart(2, 0) ==
                      game.teamHomeScore
                    "
                  >
                    <span>{{ number - 1 }}</span>
                  </option>
                </select>
                <span class="delimiter"> : </span>
                <select v-model="game.teamAwayScore">
                  <option
                    v-for="number in 101"
                    :key="number + 'teamAwayScore'"
                    :value="(number - 1).toString().padStart(2, 0)"
                    :selected="
                      (number - 1).toString().padStart(2, 0) ==
                      game.teamAwayScore
                    "
                  >
                    <span>{{ number - 1 }}</span>
                  </option>
                </select>
                <span class="team2">
                  <select v-model="game.teamAwayId">
                    <option
                      v-for="team in teamsSeasonly"
                      :key="team.id"
                      :selected="game.teamAwayId == team.team_id"
                      :value="team.team_id"
                      v-show="team.year === activeYear"
                    >
                      {{ team.name }}
                    </option>
                  </select>
                </span>
              </div>
              <div class="match_info">
                <span class="time">
                  <input type="date" v-model="game.dateInput" />
                  <font-awesome-icon :icon="['fas', 'clock']" />
                  <input type="time" v-model="game.time" />
                </span>
                <span class="referee">
                  <select v-model="game.refereeId">
                    <option
                      v-for="team in teamsSeasonly"
                      :selected="game.refereeId == team.team_id"
                      :key="team.id"
                      :value="team.team_id"
                      v-show="team.year === activeYear"
                    >
                      {{ team.name }}
                    </option>
                  </select>
                  <img src="../../public/images/whistle.svg" />
                </span>
              </div>
              <div class="actionButtons">
                <font-awesome-icon
                  :icon="['fas', 'circle-arrow-down']"
                  class="fa-2x"
                  @click="game.shown = !game.shown"
                  :class="{ rotate: game.shown }"
                />
                <button @click="deleteGame(game.gameId)" class="btn delete">
                  Smazat
                </button>
                <button @click="updateGame(game)" class="btn btn-primary">
                  Potvrdit změny
                </button>
              </div>
              <div
                class="players_stats"
                v-for="gameStat in game.stats"
                :key="gameStat.id"
              >
                <div
                  class="players_stats__wrapper"
                  v-show="game.shown"
                  :id="'players_stats-' + game.gameId"
                  v-for="statWrapper in gameStat.data"
                  :key="statWrapper.id"
                >
                  <h2>{{ teamIndexToName(statWrapper.value) }}</h2>
                  <div class="row">
                    <h2>Statistiky</h2>
                    <h2>Počet doběhů</h2>
                    <h2>Počet HR</h2>
                  </div>
                  <template v-for="stat in statWrapper.data">
                    <div
                      class="row"
                      v-if="stat.game_id === game.gameId"
                      :key="stat.id"
                    >
                      <select v-model="stat.player_id">
                        <template v-for="team in players">
                          <option
                            disabled
                            :key="team.id"
                            v-show="
                              game.teamHomeId === team.value ||
                              game.teamAwayId === team.value
                            "
                          >
                            {{ team.data[0].team_name }}
                          </option>
                          <option
                            v-for="player in team.data"
                            :key="player.id"
                            :value="player.id"
                            :selected="stat.player_id == player.id"
                            v-show="
                              game.teamHomeId === player.team_id ||
                              game.teamAwayId === player.team_id
                            "
                          >
                            {{ player.player_name }}
                          </option>
                        </template>
                      </select>
                      <input type="text" v-model="stat.run_count" />
                      <input type="text" v-model="stat.hr_count" />
                      <font-awesome-icon
                        :icon="['fas', 'square-xmark']"
                        class="delete fa-2x"
                        @click="deleteStat(game, statWrapper.value, stat.id)"
                      />
                    </div>
                  </template>
                </div>
              </div>
              <div
                class="actionButtons"
                v-show="game.shown"
                :class="{ shown: game.shown }"
              >
                <font-awesome-icon
                  :icon="['fas', 'circle-plus']"
                  class="plus"
                  @click="addStat(game.gameId)"
                />
                <button @click="updateStats(game)" class="btn btn-primary">
                  Potvrdit změny
                </button>
              </div>
            </div>
          </template>
        </div>
      </div>

      <div class="col-xl-6" id="final">
        <h1>Finálový turnaj</h1>
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
          <input type="date" v-model="gameDate.data[0].dateInput" />
          <button
            @click="changeDate(gameDate.data[0].dateInput, gameDate)"
            class="btn btn-primary"
          >
            Změň datumy
          </button>
          <div
            class="match_wrapper"
            v-for="game in gameDate.data"
            :key="game.id"
          >
            <div class="match">
              <span class="team1">
                <select v-model="game.teamHomeId">
                  <option
                    v-for="team in teamsSeasonly"
                    :key="team.id"
                    :selected="game.teamHomeId == team.team_id"
                    :value="team.team_id"
                    v-show="team.year === activeYear"
                  >
                    {{ team.name }}
                  </option>
                </select>
              </span>
              <select v-model="game.teamHomeScore">
                <option
                  v-for="number in 101"
                  :key="number"
                  :value="(number - 1).toString().padStart(2, 0)"
                  :selected="
                    (number - 1).toString().padStart(2, 0) == game.teamHomeScore
                  "
                >
                  <span>{{ number - 1 }}</span>
                </option>
              </select>
              <span class="delimiter"> : </span>
              <select v-model="game.teamAwayScore">
                <option
                  v-for="number in 101"
                  :key="number"
                  :value="(number - 1).toString().padStart(2, 0)"
                  :selected="
                    (number - 1).toString().padStart(2, 0) == game.teamAwayScore
                  "
                >
                  <span>{{ number - 1 }}</span>
                </option>
              </select>
              <span class="team2">
                <select v-model="game.teamAwayId">
                  <option
                    v-for="team in teamsSeasonly"
                    :key="team.id"
                    :selected="game.teamAwayId == team.team_id"
                    :value="team.team_id"
                    v-show="team.year === activeYear"
                  >
                    {{ team.name }}
                  </option>
                </select>
              </span>
            </div>

            <div class="match_info">
              <span class="time">
                <input type="date" :value="game.dateInput" disabled />
                <font-awesome-icon :icon="['fas', 'clock']" />
                <input type="time" v-model="game.time" />
              </span>
              <span class="referee">
                <select v-model="game.refereeId">
                  <option
                    v-for="team in teamsSeasonly"
                    :selected="game.refereeId == team.team_id"
                    :key="team.id"
                    :value="team.team_id"
                    v-show="team.year === activeYear"
                  >
                    {{ team.name }}
                  </option>
                </select>
                <img src="../../public/images/whistle.svg" />
              </span>
            </div>
            <div class="actionButtons">
              <font-awesome-icon
                :icon="['fas', 'circle-arrow-down']"
                class="fa-2x"
                @click="game.shown = !game.shown"
                :class="{ rotate: game.shown }"
              />
              <button @click="deleteGame(game.gameId)" class="btn delete">
                Smazat
              </button>
              <button @click="updateGame(game)" class="btn btn-primary">
                Potvrdit změny
              </button>
            </div>
            <div
              class="players_stats"
              v-for="gameStat in game.stats"
              :key="gameStat.id"
            >
              <div
                class="players_stats__wrapper"
                v-show="game.shown"
                :id="'players_stats-' + game.gameId"
                v-for="statWrapper in gameStat.data"
                :key="statWrapper.id"
              >
                <h2>{{ teamIndexToName(statWrapper.value) }}</h2>
                <div class="row">
                  <h2>Statistiky</h2>
                  <h2>Počet doběhů</h2>
                  <h2>Počet HR</h2>
                </div>
                <template v-for="stat in statWrapper.data">
                  <div
                    class="row"
                    v-if="stat.game_id === game.gameId"
                    :key="stat.id"
                  >
                    <select v-model="stat.player_id">
                      <template v-for="team in players">
                        <option
                          disabled
                          :key="team.id"
                          v-show="
                            game.teamHomeId === team.value ||
                            game.teamAwayId === team.value
                          "
                        >
                          {{ team.data[0].team_name }}
                        </option>
                        <option
                          v-for="player in team.data"
                          :key="player.id"
                          :value="player.id"
                          :selected="stat.player_id == player.id"
                          v-show="
                            game.teamHomeId === player.team_id ||
                            game.teamAwayId === player.team_id
                          "
                        >
                          {{ player.player_name }}
                        </option>
                      </template>
                    </select>
                    <input type="text" v-model="stat.run_count" />
                    <input type="text" v-model="stat.hr_count" />
                    <font-awesome-icon
                      :icon="['fas', 'square-xmark']"
                      class="delete fa-2x"
                      @click="deleteStat(game, statWrapper.value, stat.id)"
                    />
                  </div>
                </template>
              </div>
            </div>
            <div
              class="actionButtons"
              v-show="game.shown"
              :class="{ shown: game.shown }"
            >
              <font-awesome-icon
                :icon="['fas', 'circle-plus']"
                class="plus"
                @click="addStat(game.gameId)"
              />
              <button @click="updateStats(game)" class="btn btn-primary">
                Potvrdit změny
              </button>
            </div>
          </div>
          <div
            v-for="season in seasons"
            :key="season.id"
            v-show="activeYear === season.year"
          >
            <div class="homerun-master">
              <h2>HomeRun Master</h2>

              <select v-model="season.HR_master_id">
                <template v-for="team in players">
                  <option disabled :key="team.id">
                    {{ team.data[0].team_name }}
                  </option>
                  <option
                    v-for="player in team.data"
                    :key="player.id"
                    :value="player.id"
                    :selected="season.HR_master_id == player.id"
                  >
                    {{ player.player_name }}
                  </option>
                </template>
              </select>
              <button @click="updateMVP(season, 0)" class="btn btn-primary">
                Potvrdit změny
              </button>
            </div>

            <div class="MVP">
              <h2>Ocenění pro nejúžitečnejšího hráče/ku MVP</h2>
              <select v-model="season.MVP_id">
                <template v-for="team in players">
                  <option disabled :key="team.id">
                    {{ team.data[0].team_name }}
                  </option>
                  <option
                    v-for="player in team.data"
                    :key="player.id"
                    :value="player.id"
                    :selected="season.MVP_id == player.id"
                  >
                    {{ player.player_name }}
                  </option>
                </template>
              </select>
              <button @click="updateMVP(season, 1)" class="btn btn-primary">
                Potvrdit změny
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="team-members">
      <h1>Týmy</h1>
      <h2>{{ activeYear }}</h2>
      <font-awesome-icon
        :icon="['fas', 'circle-plus']"
        class="plus"
        @click="addPlayer()"
      />
      <div class="row container">
        <div class="col-md-4 teamlist">
          <div
            class="teamlist__teamWrapper"
            v-for="team in checkedTeams"
            :key="team.id"
          >
            <input
              type="checkbox"
              v-model="team.checked"
              @click="updateActiveTeams(team)"
            />
            <h2 @click="activeTeam = team.id">
              {{ team.name }}
            </h2>
          </div>
        </div>
        <div class="col-md-8">
          <ul
            class="players"
            v-for="team in players"
            :key="team.id"
            v-show="team.value === activeTeam"
          >
            <li v-for="player in team.data" :key="player.id">
              &#35;
              <input
                type="text"
                class="number"
                v-model="player.number"
                size="3"
              />
              <input type="text" v-model="player.player_name" />
              <input
                type="checkbox"
                :name="player.player_name"
                :id="player.player_name"
                :checked="player.isActive"
                @click="
                  (player.isActive = !player.isActive ? 1 : 0),
                    updatePlayer(player)
                "
              />
              <div class="actionButtons">
                <button class="btn save" @click="updatePlayer(player)">
                  Uložit
                </button>
                <!--<button class="btn delete" @click="deletePlayer(player.id)">
                  Smazat
                </button>-->
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="seasons row container">
      <div
        class="row finalRanks"
        v-for="season in finalRanks"
        :key="season.id"
        v-show="season.value === activeYear"
      >
        <div class="column" v-for="(team, index) in season.data" :key="team.id">
          <template v-if="index < 3">
            <img :src="`../images/logos/teams/${team.image_path}`" alt="" />
            <font-awesome-icon :icon="['fas', 'trophy']" class="fa-2x" />
            <button @click="deleteRank(team.id)" class="btn delete">
              Smazat
            </button>
          </template>
          <template v-else>
            <img :src="`../images/logos/teams/${team.image_path}`" alt="" />
            <span>{{ index + 1 }}</span>
            <button @click="deleteRank(team.id)" class="btn delete">
              Smazat
            </button>
          </template>
        </div>
      </div>
      <div class="plusRank">
        <font-awesome-icon
          :icon="['fas', 'circle-plus']"
          class="plus"
          @click="addRank(selectedTeam)"
        />

        <select v-model="selectedTeam">
          <option
            v-for="team in teamsSeasonly"
            :key="team.id"
            :value="team.team_id"
            v-show="team.year === activeYear"
          >
            {{ team.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="photos row team-members">
      <input
        type="file"
        name="photos"
        id="photos"
        @change="addPhotos"
        multiple
      />
      <button class="btn btn-primary" @click="addPhotosDone()">
        Přidat fotky
      </button>
      <div>
        <h3>{{ progress }} / {{ photoCount }}</h3>
        <progress id="file" :max="photoCount" :value="progress"></progress>
      </div>
    </div>
    <div class="seasons row container">
      <div class="seasons__wrapper" v-for="season in seasons" :key="season.id">
        <label
          :for="'season-' + season.id"
          @click="makeSeasonActive(season.id)"
          >{{ season.year }}</label
        >
        <input
          type="radio"
          name="season"
          :id="'season-' + season.id"
          :checked="season.year === activeYear"
        />
      </div>
      <font-awesome-icon
        :icon="['fas', 'circle-plus']"
        class="plus"
        @click="addSeason()"
      />
    </div>
  </div>
</template>
<script>
import axios from "../api";

export default {
  components: {},

  data() {
    return {
      logMessage: "",
      photos: [],
      selectedTeam: 0,
      games: [],
      progress: 0,
      photoCount: 0,
      teamsSeasonly: [],
      activeTeam: 1,
      activeYear: "",
      leaugeData: [],
      stats: [],
      finalData: [],
      finalRanks: [],
      seasons: [],
      players: [],
      teams: [],
      newStatId: 1,
      tokenData: this.$route.params,
      statsToDelete: [],
    };
  },

  methods: {
    addGame(isFinal) {
      let seasonId;
      this.seasons.forEach((season) => {
        if (season.year === this.activeYear) {
          seasonId = season.id;
          return seasonId;
        }
      });

      let game = {
        isFinal: isFinal,
        seasonId: seasonId,
      };
      axios
        .post(
          "api/game/store",
          { game },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 201) {
            console.log("Úspěch");
            this.$emit(this.getGames());
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    teamIndexToName(teamId) {
      if (teamId === "null") return "Nezařazeno";
      return this.teams.filter((item) => item.id == teamId)[0]?.name;
    },

    addPlayer() {
      let data = {
        teamId: this.activeTeam,
      };
      axios
        .post(
          "api/player/store",
          { data },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 201) {
            console.log("Úspěch");
            this.$emit(this.getPlayers());
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    addRank(team) {
      let seasonId;
      this.seasons.forEach((season) => {
        if (season.year === this.activeYear) {
          seasonId = season.id;
          return seasonId;
        }
      });
      let data = {
        teamId: team,
        seasonId: seasonId,
      };
      axios
        .post(
          "api/rank/store",
          { data },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 201) {
            console.log("Úspěch");
            this.$emit(this.getFinalRanks());
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    addSeason() {
      let data = {
        seasonYear: this.seasons[this.seasons.length - 1].year + 1,
      };
      axios
        .post(
          "api/season/store",
          { data },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 201) {
            console.log("Úspěch");
            this.$emit(this.getSeasons());
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    async getStats() {
      try {
        const response = await axios.get("/api/stats");
        let groupedById = this.groupByKey(response.data, "game_id");
        for (let group of groupedById) {
          this.stats.push({
            value: group.value,
            data: this.groupByKey(group.data, "team_id"),
          });
        }
      } catch (error) {
        console.log(error);
      }
    },

    makeSeasonActive(seasonId) {
      axios
        .put("/api/season/active/" + seasonId)
        .then((response) => {
          if (response.status == 200) {
            console.log("Úspěch");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    async getPlayers() {
      try {
        const response = await axios.get("/api/players");
        this.players = this.groupByKey(response.data, "team_id");
      } catch (error) {
        console.log(error);
      }
    },
    async getTeamsBySeason() {
      try {
        const response = await axios.get("/api/teams/seasonly");
        this.teamsSeasonly = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    getTeams() {
      axios
        .get("/api/teams")
        .then((response) => {
          this.teams = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getSeasons() {
      axios
        .get("/api/seasons")
        .then((response) => {
          this.seasons = response.data;
          this.seasons.forEach((season) => {
            if (season.isActual) {
              this.activeYear = season.year;
              return;
            }
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },

    deleteGame(gameId) {
      this.$confirm({
        message: `Opravdu chceš tento zápas smazat?`,
        button: {
          yes: "Ano",
          no: "Ne",
        },
        callback: (confirm) => {
          if (confirm) {
            axios
              .delete("/api/game/" + gameId, {
                headers: {
                  Authorization: this.tokenData.data.token,
                },
              })
              .then((response) => {
                if (response.status === 200) {
                  console.log("Zápas smazán");
                  this.$emit(this.getGames());
                }
              })
              .catch((error) => {
                console.log(error);
              });
          }
        },
      });
    },

    addPhotos(event) {
      this.photos = new FormData();
      event.target.files.forEach((element) => {
        this.photos.append("images[]", element);
        this.photoCount++;
      });
    },

    addPhotosDone() {
      let photos = this.photos;
      let seasonId;
      this.seasons.forEach((season) => {
        if (season.year === this.activeYear) {
          seasonId = season.id;
          return seasonId;
        }
      });

      const config = {
        onUploadProgress: function (progressEvent) {
          var percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
          if (percentCompleted == 100) {
            this.progress++;
          }
        }.bind(this),
      };

      photos.forEach((photo) => {
        let newPhoto = new FormData();
        newPhoto.append("images[]", photo);

        axios
          .post(`api/photos/${seasonId}/store`, newPhoto, config, {
            headers: {
              Authorization: this.tokenData.data.token,
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            if (response.status == 201) {
              console.log("Úspěch");
            }
          })
          .catch((error) => {
            console.log(error);
          });
      });
    },

    /*  axios
        .post(
          "api/game/store",
          { game },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 201) {
            console.log("Úspěch");
            this.$emit(this.getGames());
          }
        })
        .catch((error) => {
          console.log(error);
        });
      */

    updateActiveTeams(team) {
      this.seasons.forEach((season) => {
        if (season.year === this.activeYear) {
          team.season_id = season.id;
        }
      });

      if (!team.checked) {
        axios
          .post(
            "/api/team/active/store",
            { team },
            {
              headers: {
                Authorization: this.tokenData.data.token,
              },
            }
          )
          .then((response) => {
            if (response.status === 200) {
              console.log("Tým nahrán");
              this.$emit(this.getTeamsBySeason);
            }
          })
          .catch((error) => {
            console.log(error);
          });
        return;
      } else {
        axios
          .delete("/api/team/active/" + team.seasonTeamId.id, {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          })
          .then((response) => {
            if (response.status === 200) {
              console.log("Tým smazán");
              this.$emit(this.getTeamsBySeason);
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },

    changeDate(date, data) {
      data.data.forEach((game) => {
        game.dateInput = date;
        this.updateGame(game);
      });
      this.$emit(this.getGames());
    },

    /* deletePlayer(gameId) {
      this.$confirm({
        message: `Opravdu chceš tohoto hráče smazat?`,
        button: {
          yes: "Ano",
          no: "Ne",
        },
        callback: (confirm) => {
          if (confirm) {
            axios
              .delete("/api/player/" + gameId)
              .then((response) => {
                if (response.status === 200) {
                  console.log("Zápas smazán");
                  this.$emit(this.getPlayers());
                }
              })
              .catch((error) => {
                console.log(error);
              });
          }
        },
      });
    },*/

    updatePlayer(player) {
      axios
        .put(
          "/api/player/" + player.id,
          { player },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 200) {
            console.log("Úspěch");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    updateMVP(season) {
      axios
        .put(
          "/api/season/" + season.id,
          { season },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 200) {
            console.log("Úspěch");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    updateGame(game) {
      axios
        .put(
          "/api/game/" + game.gameId,
          { game },
          {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          }
        )
        .then((response) => {
          if (response.status == 200) {
            console.log("Úspěch");
            this.logMessage = "Zápas upraven (asi) :P";
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    addStat(gameId) {
      let exists = false;
      this.newStatId++;
      let newObject = {
        id: this.newStatId + "statId",
        hr_count: 0,
        run_count: 0,
        player_id: null,
        name: null,
        game_id: gameId,
        team_id: "null",
        team_name: null,
      };
      this.games.find((item) => {
        if (item.gameId === gameId) {
          if (item.stats[0]?.data === undefined) {
            item.stats.push({
              value: gameId,
              data: [],
            });
          }
          item.stats[0]?.data.find((element) => {
            if (element.value === "null") {
              exists = true;
              element.data.push(newObject).focus();
            }
          });
          if (!exists) {
            item.stats[0].data
              .push({
                value: "null",
                data: [newObject],
              })
              .focus();
          }
        }
      });
    },

    deleteRank(teamId) {
      axios
        .delete("/api/rank/" + teamId, {
          headers: {
            Authorization: this.tokenData.data.token,
          },
        })
        .then((response) => {
          if (response.status === 200) {
            this.$emit(this.getFinalRanks());
            console.log("Tým z pořadí smazán");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    updateStats(game) {
      this.statsToDelete.forEach((itemToDelete) => {
        axios
          .delete("/api/stat/" + itemToDelete.id, {
            headers: {
              Authorization: this.tokenData.data.token,
            },
          })
          .then((response) => {
            if (response.status === 200) {
              console.log("Zápas smazán");
            }
          })
          .catch((error) => {
            console.log(error);
          });
      });
      game.stats[0].data.forEach((statWrapper) => {
        statWrapper.data.forEach((element) => {
          if (element.team_id === "null") {
            if (
              !(
                element.player_id === null ||
                (element.run_count == 0 && element.hr_count == 0)
              )
            ) {
              axios
                .post(
                  "api/stat/store",
                  { element },
                  {
                    headers: {
                      Authorization: this.tokenData.data.token,
                    },
                  }
                )
                .then((response) => {
                  if (response.status == 201) {
                    //emit něčeho i guess
                  }
                })
                .catch((error) => {
                  console.log(error);
                });
            }
          } else {
            axios
              .put(
                "/api/stat/" + element.id,
                { element },
                {
                  headers: {
                    Authorization: this.tokenData.data.token,
                  },
                }
              )
              .then((response) => {
                if (response.status == 200) {
                  console.log("Úspěch");
                }
              })
              .catch((error) => {
                console.log(error);
              });
          }
        });
      });
    },

    deleteStat(game, teamId, statId) {
      this.$confirm({
        message: `Opravdu chceš tento záznam smazat ze statistik?`,
        button: {
          yes: "Ano",
          no: "Ne",
        },

        callback: (confirm) => {
          if (confirm) {
            game.stats[0].data.find((item) => {
              if (item.value === teamId) {
                item.data.find((element, i) => {
                  if (element?.id === statId) {
                    item.data.splice(i, 1);
                    if (typeof element.id == "number") {
                      this.statsToDelete.push(element);
                    }
                  }
                });
              }
            });
          }
        },
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
        if (item.team_id == null) {
          item.team_id = "null";
        }
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
    async getGames() {
      try {
        const response = await axios.get("/api/games");
        this.games = response.data.map((game) => {
          return {
            gameId: game.id,
            dateInput: game.time.split(" ")[0],
            date: new Date(game.time.split(" ")[0]).toLocaleDateString(),
            day: this.indexToDay(new Date(game.time).getDay()),
            time: game.time.split(" ")[1].toString().substring(0, 5),
            teamHomeScore: game.team_home_score.toString().padStart(2, 0),
            teamAwayScore: game.team_away_score.toString().padStart(2, 0),
            teamHome: game.team_home_name,
            teamAway: game.team_away_name,
            teamHomeId: game.team_home_id,
            teamAwayId: game.team_away_id,
            isActual: game.isActual,
            isFinal: game.isFinal,
            seasonYear: game.year,
            homeTeamImage: game.image_path_home,
            awayTeamImage: game.image_path_away,
            referee: game.referee_name,
            refereeId: game.referee,
            shown: false,
            stats: this.stats.filter((item) => item.value === game.id),
          };
        });
        let groupedByYear = this.groupByKey(
          this.separateGames(this.games).leauge,
          "seasonYear"
        );
        let groupedByYearAndDate = [];
        for (let group of groupedByYear) {
          groupedByYearAndDate.push({
            value: group.value,
            data: this.groupByKey(group.data, "date"),
          });
        }
        this.leaugeData = groupedByYearAndDate;
        this.finalData = this.groupByKey(
          this.separateGames(this.games).final,
          "seasonYear"
        );
      } catch (error) {
        console.log(error);
      }
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
    async getFinalRanks() {
      try {
        const response = await axios.get("/api/finalRanks");
        this.finalRanks = response.data;
        this.finalRanks = this.groupByKey(this.finalRanks, "year");
      } catch (error) {
        console.log(error);
      }
    },
  },
  created() {
    this.getStats().then(this.getGames);
    this.getTeams();
    this.getSeasons();
    this.getPlayers();
    this.getTeamsBySeason();
    this.getFinalRanks();
  },

  computed: {
    checkedTeams: function () {
      return this.teams.map((team) => {
        team.checked = this.teamsSeasonly.some(
          (item) => item.team_id === team.id && item.year === this.activeYear
        );
        team.seasonTeamId = this.teamsSeasonly.find((item) => {
          if (item.team_id == team.id && item.year === this.activeYear) {
            return item;
          }
        });
        return team;
      });
    },
  },
};
</script>

<style lang="scss">
.photos {
  display: flex;
  align-items: center;

  button {
    border: 1px solid white;
  }

  & > * {
    margin: 0;
    margin-right: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
}

.column {
  .delete {
    background: #dc3545;
    margin-top: 0.7rem;
    color: white !important;
  }
  span {
    height: 28.8px;
  }
}
.seasons {
  display: flex;
  align-items: center;
  &__wrapper {
    background: #12284b;
    color: white;
    margin: 1rem;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    position: relative;

    label {
      cursor: pointer;
      margin: 0;
      padding: 0.7rem 2rem 0.7rem 1rem;
    }
    input {
      position: absolute;
      cursor: pointer;
      right: 0.6rem;
      pointer-events: none;
    }
  }
  .plus {
    color: #12284b;
    margin: 0;
  }
}
.plusRank {
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>>