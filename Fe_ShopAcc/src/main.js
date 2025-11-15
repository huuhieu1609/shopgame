import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import Default from './layout/wrapper/index.vue'
import IndexClient from './layout/wrapper/indexClient.vue'
import IndexBlank from './layout/wrapper/indexBlank.vue'
const app = createApp(App)

app.use(router)
app.component("default-layout", Default);
app.component("indexClient-layout", IndexClient);
app.component("indexBlank-layout", IndexBlank);
app.mount("#app")