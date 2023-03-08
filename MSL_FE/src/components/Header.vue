<template>
  <div class="header-wrapper">
    <nav class="navbar sticky navbar-dark navbar-expand-lg bg-primary">
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav text-md-center nav-justified w-100">
          <li class="nav-item">
            <a class="nav-link" href="#o-lize">O lize</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#tymy">Týmy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#zapasy">Zápasy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#tabulka">Tabulka</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#pravidla">Pravidla</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#registrace">Registrace</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#gallery">Galerie</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="welcome invisible">
      <img
        src="../../public/images/logos/logo-msl.svg"
        alt="MSL logo"
        class="logo"
      />
      <div class="name">Městská Slowpitchová Liga<br />Choceň</div>
      <div class="teams">
        <img
          src="../../public/images/logos/teams/Bounty of Tigers.svg"
          alt="Bounty of Tigers"
          class="w4"
        />
        <img
          src="../../public/images/logos/teams/Thunder Chickens.svg"
          alt="Thunder Chickens"
          class="w3"
        />
        <img
          src="../../public/images/logos/teams/Kung-Fu Pandas.svg"
          alt="Kung-Fu Pandas"
          class="w2"
        />
        <img
          src="../../public/images/logos/teams/Funky Monkey.svg"
          alt="Funky Monkey"
          class="w1"
        />
        <img
          src="../../public/images/logos/teams/Jalapeno Koalas.svg"
          alt="Jalapeno Koalas"
          class="w1"
        />
        <img
          src="../../public/images/logos/teams/Rabbit Paws.svg"
          alt="Rabbit Paws"
          class="w2"
        />
        <img
          src="../../public/images/logos/teams/Shaolin Mooses.svg"
          alt="Shaolin Mooses"
          class="w3"
        />
        <img
          src="../../public/images/logos/teams/Dog Riders.svg"
          alt="Dog Riders"
          class="w4"
        />
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      //   publicPath: process.env.BASE_URL,
    };
  },
};
</script>