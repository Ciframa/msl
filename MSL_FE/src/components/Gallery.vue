<template>
  <div class="container">
    <h1>Galerie</h1>
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
    <div
      class="row"
      v-for="season in photos"
      :key="season.value"
      v-show="activeYear === season.value"
    >
      <img
        v-for="photo in season.data"
        :src="`http://localhost:8000/images/${photo.path}`"
        alt=""
        :key="photo.id"
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
      photos: [],
      seasons: [],
      activeYear: 0,
    };
  },

  methods: {
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

    async getPhotos() {
      try {
        const response = await axios.get("/api/photos");
        this.photos = response.data;
        this.photos = this.groupByKey(this.photos, "season_id");
      } catch (error) {
        console.log(error);
      }
    },
  },
  created() {
    this.getPhotos();
    this.getSeasons();
  },
};
</script>


<style scoped>
button {
  background: white !important;
  color: #12284b !important;
  box-shadow: none !important;
}
.activeButton {
  background: #12284b !important;
  color: white !important;
}
.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  padding: 0 4px;

  margin-top: 5rem;
}

/* Create four equal columns that sits next to each other */
img {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
  max-width: 25%;
  object-fit: cover;
}

/* Responsive layout - makes a two column-layout instead of four columns */
@media screen and (max-width: 991px) {
  img {
    -ms-flex: 50%;
    flex: 50%;
    max-width: 50%;
  }
}

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  img {
    -ms-flex: 100%;
    flex: 100%;
    max-width: 100%;
  }
}
</style>