<template>
   <div class="app-container">
     <el-table
       :data="listPageInfo.devices"
       style="width: 100%">
       <el-table-column prop="id" label="ID" width="50"/>
       <el-table-column prop="deviceId" label="设备ID" />
       <el-table-column prop="ipAddress" label="ip" />
       <el-table-column prop="status" label="状态" />
       <el-table-column prop="playState" label="播放状态" />
       <el-table-column prop="playMode" label="播放模式" />
       <el-table-column prop="playSound" label="音量" />
       <el-table-column prop="alias" label="别名" />
       <el-table-column prop="categoryName" label="分类" />
       <el-table-column prop="fileCnt" label="文件数量" />
       <el-table-column prop="fileCurrent" label="当前文件" />
       <el-table-column prop="memorySize" label="内存" />
       <el-table-column prop="createdAt" label="创建时间" />
     </el-table>
   </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import {DevicesModule} from '@/store/modules/deviceListPage'
import {DeviceListPageType} from "@/typings";
@Component({
  name: 'devices'
})
export default class extends Vue {

  get listPageInfo(): DeviceListPageType
  {
    return DevicesModule.deviceListPage
  }

  private loading = false

  /**
   *  获取数据
   * @param page
   * @private
   */
  private fetchData(page: number): void
  {
    this.loading = true
    try {
      DevicesModule.GetDeviceListPage(page)
    }finally {
      this.loading = false
    }
  }

  mounted()
  {
    this.fetchData(this.listPageInfo.page)
  }

}
</script>

<style scoped>

</style>
