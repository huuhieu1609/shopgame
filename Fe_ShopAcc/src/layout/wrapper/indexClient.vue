<template>
    <div class="client-layout">
        <menu-client></menu-client>
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <bot-client></bot-client>
    </div>
</template>
<script>   
import	"../../assets/js/pace.min.js";
import	"../../assets/js/bootstrap.bundle.min.js";
import	"../../assets/js/jquery.min.js";
import	"../../assets/plugins/simplebar/js/simplebar.min.js";
import	"../../assets/plugins/metismenu/js/metisMenu.min.js";
// import	"../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js";
import	"../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js";
import	"../../assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js";
import	"../../assets/plugins/chartjs/js/Chart.min.js";
import	"../../assets/plugins/chartjs/js/Chart.extension.js";
import	"../../assets/js/index.js";
import	"../../assets/js/app.js";

import MenuClient from '../components/Client/MenuClient.vue';  
import BotClient from '../components/Client/BotClient.vue';
export default {
  components: { MenuClient, BotClient },
      
}

</script>
<style>
@import	"../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css";
@import	"../../assets/plugins/simplebar/css/simplebar.css";
@import	"../../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css";
@import	"../../assets/plugins/metismenu/css/metisMenu.min.css";
@import	"../../assets/css/pace.min.css";
@import	"../../assets/css/bootstrap.min.css";
@import	"../../assets/css/bootstrap-extended.css";
@import	"../../assets/css/app.css";
@import	"../../assets/css/icons.css";
@import	"../../assets/css/dark-theme.css";
@import	"../../assets/css/semi-dark.css";
@import	"../../assets/css/header-colors.css";
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap");

/* Client Layout */
.client-layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.content-wrapper {
    flex: 1;
    padding-top: 70px; /* Chiều cao của menu header */
    min-height: calc(100vh - 70px);
    display: flex;
    flex-direction: column;
}

</style>