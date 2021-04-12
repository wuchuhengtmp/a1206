<template>
   <div class="app-container">
     <el-row :gutter="30">
       <el-col :span="24">
         <el-table
           :data="listPageInfo.devices"
           v-loading="loading"
           style="width: 100%">
           <el-table-column prop="id" label="ID" width="50"/>
           <el-table-column prop="deviceId" label="设备ID" />
           <el-table-column prop="ipAddress" label="ip" />
           <el-table-column prop="status" label="状态" >
             <template slot-scope="scope">
               <el-tag type="success" v-if="scope.row.status === 'online'">在线</el-tag>
               <el-tag type="danger" v-else>离线</el-tag>
             </template>
           </el-table-column>
           <el-table-column prop="playState" label="播放状态" >
             <template slot-scope="scope">
               <el-tag type="success" v-if="scope.row.playState === 1">播放</el-tag>
               <el-tag type="waring" v-else-if="scope.row.playState === 2">暂停</el-tag>
               <el-tag type="danger" v-else>停止</el-tag>
             </template>
           </el-table-column>
           <el-table-column prop="playMode" label="播放模式">
             <template slot-scope="scope">
               <el-tag type="success" v-if="scope.row.playMode === 0">顺序播放</el-tag>
               <el-tag type="success" v-if="scope.row.playMode === 1">循环播放一次</el-tag>
               <el-tag type="success" v-if="scope.row.playMode === 2">随机播放</el-tag>
               <el-tag type="success" v-if="scope.row.playMode === 3">随机播放</el-tag>
               <el-tag type="success" v-if="scope.row.playMode === 4">顺序播放</el-tag>
               <el-tag type="success" v-if="scope.row.playMode === 5">循环播放</el-tag>
               <el-tag type="success" v-if="scope.row.playMode === 6">顺序播放全部</el-tag>
               <el-tag type="success" v-if="scope.row.playMode === 7">顺序播放一次</el-tag>
             </template>
           </el-table-column>
           <el-table-column prop="playSound" label="音量" />
           <el-table-column prop="alias" label="别名" />
           <el-table-column prop="categoryName" label="分类" />
           <el-table-column prop="fileCnt" label="文件数量" />
           <el-table-column prop="fileCurrent" label="当前文件" />
           <el-table-column prop="memorySize" label="内存" />
           <el-table-column prop="createdAt" label="创建时间" />
         </el-table>
       </el-col>
     </el-row>
     <el-row :gutter="30">
       <el-col :span="24" :align="'right'">
         <el-pagination
           background
           layout="prev, pager, next"
           v-show="listPageInfo.total > 0"
           :total="listPageInfo.total"
           @current-change="handlePageChange"
         />
      </el-col>
     </el-row>
   </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { DevicesModule } from '@/store/modules/deviceListPage'
import { DeviceListPageType } from '@/typings'
@Component({
  name: 'devices'
})
export default class extends Vue {
  get listPageInfo(): DeviceListPageType {
    return DevicesModule.deviceListPage
  }

  private loading = false

  /**
   *  获取数据
   * @param page
   * @private
   */
  private fetchData(page: number): void {
    this.loading = true
    try {
      DevicesModule.GetDeviceListPage(page)
    } finally {
      this.loading = false
    }
  }

  mounted() {
    this.fetchData(this.listPageInfo.page)
  }

  /**
   *  加载分页
   * @param page
   * @private
   */
  private handlePageChange(page: number): void {
    this.fetchData(page)
  }
}
</script>

<style scoped>

</style>
