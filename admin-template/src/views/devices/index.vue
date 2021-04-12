<template>
   <div class="app-container">
     <el-form :inline="true" :model="form" class="demo-form-inline">
       <el-form-item label="账号">
         <el-input v-model="form.username" placeholder="账号"></el-input>
       </el-form-item>
       <el-form-item label="设备状态">
         <el-select v-model="form.status" placeholder="状态">
           <el-option label="全部" value="all"></el-option>
           <el-option label="在线" value="online"></el-option>
           <el-option label="离线" value="offline"></el-option>
         </el-select>
       </el-form-item>
       <el-form-item>
         <el-button type="primary" @click="handleSearch" >查找</el-button>
       </el-form-item>
     </el-form>
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
           :current-page="listPageInfo.page"
           @current-change="handlePageChange"
         />
      </el-col>
     </el-row>
   </div>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator'
import { DevicesModule } from '@/store/modules/deviceListPage'
import { DeviceListPageType, DeviceQueryType } from '@/typings'
import { Route } from 'vue-router'
import { getHash, obj2Query, query2Obj } from '@/utils/helper'
@Component({
  name: 'devices'
})
export default class extends Vue {
  get listPageInfo(): DeviceListPageType {
    return DevicesModule.deviceListPage
  }

  private loading = false

  private form: DeviceQueryType = {
    username: '',
    status: 'all',
    page: '1'
  }

  /**
   *  获取数据
   * @param page
   * @private
   */
  private fetchData(): void {
    this.loading = true
    // 初始化查询参数
    for (const v in this.$route.query) {
      if (v in this.form) {
        this.form[v as keyof DeviceQueryType] = this.$route.query[v] as string
      }
    }
    try {
      DevicesModule.GetDeviceListPage(this.form)
    } finally {
      this.loading = false
    }
  }

  @Watch('$route')
  private onRouteChange(route: Route) {
    const path = route.path
    if (path === '/devices/index') {
      this.fetchData()
    }
  }

  mounted() {
    this.fetchData()
  }

  /**
   *  加载分页
   * @param page
   * @private
   */
  private handlePageChange(page: number): void {
    this.form.page = page + ''
    this.handleSearch()
  }

  /**
   *
   * @private
   */
  private handleSearch() {
    this.form.page = 1 + ''
    const query = query2Obj(obj2Query(this.form))
    if (JSON.stringify(this.$route.query) !== JSON.stringify(query)) {
      this.$router.push({ path: this.$route.path, query })
    } else {
      // 刷新
      this.$router.push({ path: this.$route.path, query: { ...query, refresh: Date.now() + '' } })
    }
  }
}
</script>
0
<style scoped>

</style>
