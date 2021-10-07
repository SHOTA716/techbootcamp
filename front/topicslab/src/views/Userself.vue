<template>
  <div>
    <Card>
       <template #title>
        マイページ
      </template>
      <template #content>
        {{user.name}}
        <!-- <tr v-for="(userprof, index) in allTasks" v-bind:key="userprof.id">
          <td>
            <div v-if="!isEditing[index]">
              <span>{{ userprof.body }}</span>
            </div>
            <form v-if="isEditing[index]" @submit.prevent="editTask(index, task)">
              <input type="text" :value="userprof.body" :ref="userprof.id">
              <div>
                <button type="submit">保存</button>
                <button type="button" @click="isEditing[index] = false">キャンセル</button>
              </div>
            </form>
          </td>
       </tr> -->
        <Textarea v-model="value" rows="5" cols="30" placeholder="自己紹介を追加"/>
        <Button label="プロフィールを公開" v-on:click="submit" />
        <TabView>
          <TabPanel header="Topics">
            {{user.topics}}
          </TabPanel>
          <TabPanel header="あなたのコメント">
            {{user.comments}}
          </TabPanel>
        </TabView>
      <Button label="トピック作成" v-on:click="toNewTopic" />
      </template>
      <template #footer>
        <Button label="ログアウト" class="p-button-warning" v-on:click="logout" />
        <Button label="退会" class="p-button-danger" v-on:click="withdraw" />
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Textarea from 'primevue/textarea'

export default {
  name: 'Userself',
  components: {
    TabView,
    TabPanel,
    Textarea
  },
  data () {
    return {
      user: {},
      value2: ''
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('/login')
      return
    }

    this.getUser()
  },
  methods: {
    async editTask (key, task) {
      task.body = this.$refs[task.id][0].value
      await this.$store.dispatch('userprof/edit', task)
      this.$set(this.isEditing, key, false)
    },
    toNewTopic () {
      this.$router.push('topic')
    },
    logout () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/logout')
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/login')
            })
            .catch(err => {
              console.log(err)
            })
        })
        .catch((err) => {
          alert(err)
        })
    },
    withdraw () {
      //
    },
    getUser () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/user')
            .then((res) => {
              if (res.status === 200) {
                this.user = res.data
              } else {
                console.log('取得失敗')
              }
            })
        })
        .catch((err) => {
          alert(err)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
  .p-card-footer {
    .p-button {
      margin-right: 10px;
    }
  }
  .box {
    background-color: var(--green-500);
    color: #ffffff;
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-radius: 4px;
    margin-top: 1rem;
    font-weight: bold;
    box-shadow: 0 2px 1px -1px rgba(0,0,0,.2), 0 1px 1px 0 rgba(0,0,0,.14), 0 1px 3px 0 rgba(0,0,0,.12);
}
@keyframes my-fadein {
    0%   { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes my-fadeout {
    0%   { opacity: 1; }
    100% { opacity: 0; }
}

.my-fadein {
    animation: my-fadein 150ms linear;
}
.my-fadeout {
    animation: my-fadeout 150ms linear;
}
</style>
