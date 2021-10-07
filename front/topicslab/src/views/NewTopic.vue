<template>
<div>
  <Card>
    <template #title>
      新しいTopicを投稿しよう
    </template>
    <template #content>
      <div class="p-field">
        <label for="title">Topicタイトル</label>
        <InputText v-model="title" id="title" type="text" aria-describedby="title-help" />
        <small id="title-help">タイトルを入力してください。</small>
        <p><span>{{messages.title}}</span></p>
      </div>
      <div class="p-field">
        <label for="title">Topic内容</label>
        <Textarea v-model="body" :autoResize="true" rows="10" />
        <p><span>{{messages.body}}</span></p>
      </div>
      <div class="p-field">
        <Button icon="pi pi-check" label="投稿する" v-on:click="submit" />
        <p><span>{{messages.submit}}</span></p>
      </div>
    </template>
  </Card>
  <!--ダイアログ表示-->
  <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
    {{messages.submit}}
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
  name: 'NewTopic',
  components: {
    Dialog
  },
  data () {
    return {
      title: '',
      body: '',
      displayBasic: false,
      messages: {
        submit: '',
        title: '',
        body: ''
      }
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('login')
    }
  },
  methods: {
    submit () {
      const title = this.title.trim()
      if (!title) {
        this.messages.title = '未記入(空白のみ)は送信できません。'
      }
      const body = this.body.trim()
      if (!body) {
        this.messages.body = '未記入(空白のみ)は送信できません。'
      }

      if (!title || !body) return

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/topic', {
            title: title,
            body: body
          })
            .then((res) => {
              if (res.status === 201) {
                this.$router.push(`/topic/${res.data.id}`)
              } else {
                this.messages.submit = '送信に失敗しました。'
              }
            })
            .catch((err) => {
              console.log(err)
              this.displayBasic = true
              this.messages.submit = '送信に失敗しました。'
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.messages.submit = '送信に失敗しました。'
        })
    },
    closeBasic () {
      this.displayBasic = false
    }
  }
}
</script>

<style scoped>
.p-field * {
  display: block;
  width: 100%;
}

span{
  color:#ff0000;
}

</style>
