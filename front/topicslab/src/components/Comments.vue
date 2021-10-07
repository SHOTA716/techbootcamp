<template>
  <div>
    <Fieldset v-for="comment in comments" :key="comment.id">
      <template #legend>
        <span v-if="comment.user === null">
          退会済みユーザー
        </span>
        <span v-else>
          <router-link :to="`/user/${comment.user_id}`">{{comment.user.name}}</router-link>
        </span>
      </template>
      <div class="comment-text">
        {{comment.body}}
      </div>
      <Button label="いいね" icon="pi pi-heart" iconPos="right" />
    </Fieldset>
  </div>
</template>

<script>
export default {
  name: 'Comments',
  props: {
    comments: Array
  }
}
</script>

<style lang="scss" scoped>
.p-fieldset {
  margin-top: 20px;
}
.comment-text {
  white-space:pre-wrap;
}
Button{
  margin-top: 2%;
}
</style>
