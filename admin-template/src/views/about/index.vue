<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="24">
        <tinymce
          v-model="value"
        />
      </el-col>
      <el-col :span="24" :align="'center'">
        <el-button
          type="primary"
          @click="handleUpdate"
        >保存</el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import Tinymce from '@/components/Tinymce/index.vue' // TinyMCE vue wrapper
import { AboutModule } from '../../store/modules/about'
import { Message } from '@/utils/helper'

@Component({
  name: 'about',
  components: { Tinymce }
})
export default class extends Vue {
  get value() {
    return AboutModule.content
  }

  set value(newContent: string) {
    AboutModule.SetContent(newContent)
  }

  mounted() {
    AboutModule.getContent()
  }

  @Message()
  private handleUpdate() {
    AboutModule.save()
  }
}
</script>

<style lang="scss">
.el-button {
   margin-top: 1rem;
}
</style>
