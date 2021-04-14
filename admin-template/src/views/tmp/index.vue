<template>
   <div>
      <input type="file" @change="(file) => handleChange(file.target.files)" />
   </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { fileToBase64 } from '@wuchuhengtools/helper'

@Component({
  name: 'tmp'
})
export default class extends Vue {
  public async handleChange(files: File[]) {
    const file = files[0]
    const b = await fileToBase64(file)
    console.log(b)
  }
}
</script>
