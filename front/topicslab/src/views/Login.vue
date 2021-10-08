<template>
  <div>
    <Card>
      <template #title>
        ログイン
      </template>
      <template #content>
        <div class="fields">
          <div class="p-field">
            <label for="email">メールアドレス</label>
            <InputText id="email" type="email" v-model="email" />
          </div>
          <div class="p-field">
            <label for="password">パスワード</label>
            <InputText id="password" type="password" v-model="password" />
          </div>
        </div>
        <span>{{message}}</span>
        <div class="p-field">
          <Button icon="pi pi-check" class="p-button-rounded" label="ログイン" v-on:click="login" />
          <router-link style="text-decoration:none;"  to ="/Register">
            <Button icon="pi pi-plus" class="p-button-rounded" label="新規登録" v-on:click="Register" />
          </router-link>
        </div>
      </template>
    </Card>
    <!--ダイアログ表示-->
    <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
      {{message}}
      <template #footer>
        <Button label="はい" icon="pi pi-check" @click="closeBasic" autofocus />
      </template>
    </Dialog>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Dialog from 'primevue/dialog'

export default {
  name: 'Login',
  components: {
    Dialog
  },
  data () {
    return {
      email: '',
      password: '',
      displayBasic: false,
      error: false,
      message: ''
    }
  },
  methods: {
    login () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/login', {
            email: this.email,
            password: this.password
          })
            .then((res) => {
              if (res.status === 200) {
                console.log('ログイン成功')
                localStorage.setItem('authenticated', 'true')
                this.$router.push('/')
              } else {
                this.message = 'ログインに失敗しました。'
              }
            })
            .catch((err) => {
              console.log(err)
              this.displayBasic = true
              this.message = 'ログインに失敗しました。'
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.message = '接続に失敗しました。'
        })
    },
    closeBasic () {
      this.displayBasic = false
    }
  }
}
</script>

<style lang="scss" scoped>
  .p-card-content {
    .fields {
      text-align: center;
    }

    .p-field {
      display: block;

      label {
        display: inline-block;
        width: 10em;
        margin-bottom: 10px;
      }

      .p-button {
        margin-top: 20px;
        display: block;
        width: 100%;
      }
    }
  }

span{
  color:#ff0000;
}

</style>
