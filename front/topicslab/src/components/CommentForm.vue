<template>
  <div>
    <Card>
      <template #content>
        <div class="p-field">
          <Textarea v-model="comment" :autoResize="true" rows="5" />
          <p><span>{{messages.submit}}</span></p>
        </div>
        <div class="p-field">
          <Button icon="pi pi-send" label="コメントする" v-on:click="submit" />
        </div>
      </template>
    </Card>
    <!--ダイアログ表示-->
    <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
      {{messages.connect}}
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
  name: 'CommentForm',
  components: {
    Dialog
  },
  props: {
    topicId: Number
  },
  data () {
    return {
      comment: '',
      displayBasic: false,
      messages: {
        submit: '',
        connect: ''
      }
    }
  },
  methods: {
    submit () {
      const comment = this.comment.trim()
      if (!comment) {
        this.messages.submit = '未記入(空白のみ)は送信できません。'
        return
      }

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/comment', {
            topicId: this.topicId,
            body: comment
          })
            .then((res) => {
              if (res.status === 201) {
                this.comment = ''
                this.$emit('sentComment', res.data)
              } else {
                this.messages.connect = '送信に失敗しました。'
              }
            })
            .catch((err) => {
              console.log(err)
              this.displayBasic = true
              this.messages.connect = '送信に失敗しました。'
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.messages.connect = '送信に失敗しました。'
        })
    },
    closeBasic () {
      this.displayBasic = false
    }
  }
}
</script>

<style scoped>
.p-card.p-component {
  margin-top:  20px;
}
.p-field * {
  display: block;
  width: 100%;
}
span{
  color: #ff0000;
}

</style>
