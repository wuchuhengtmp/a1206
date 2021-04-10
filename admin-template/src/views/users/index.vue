<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="24">
        <el-table
          :data="userListPage.users"
          style="width: 100%"
          :row-class-name="tableRowClassName"
          v-loading="loading"
        >
          <el-table-column prop="id" label="ID" width="80" />
          <el-table-column prop="username" label="用户名" width="180" />
          <el-table-column prop="avatar" label="头像">
            <el-avatar slot-scope="scope" :src="scope.row.avatar" />
          </el-table-column>
          <el-table-column prop="nickname" label="昵称" />
          <el-table-column prop="totalDevice" label="设备量" />
          <el-table-column prop="createdAt" label="创建时间" width="180" />
          <el-table-column
            label="操作"
            width="180">
            <template slot-scope="scope">
              <el-button
                size="mini"
                v-if="scope.row.totalDevice > 0"
              >查看设备</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
     </el-row>
    <el-row :gutter="20">
      <el-col :span="24" :align="'right'">
        <el-pagination
          background
          layout="prev, pager, next"
          :total="userListPage.total"
          :current-page="userListPage.page"
          @current-change="handleChange"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script lang="ts">

import { Component, Vue } from 'vue-property-decorator'
import type { UserListPageType } from '@/typings'
import { UserModule } from '@/store/modules/user'

@Component({
  name: 'Users'
})
export default class extends Vue {
  /**
   * 表格分页数据
   */
  get userListPage(): UserListPageType {
    return UserModule.userListPage
  }

  private loading = false

  /**
   *  获取分页数据
   */
  private fetchData(page:number): void {
    this.loading = true
    try {
      UserModule.GetUserList(page)
    } finally {
      this.loading = false
    }
  }

  /**
   * 翻页
   * @param res
   * @private
   */
  private handleChange(page: number) {
    this.fetchData(page)
  }

  mounted() {
    console.log(UserModule.userListPage.page)
    this.fetchData(UserModule.userListPage.page)
  }

  private tableRowClassName({ rowIndex }: {rowIndex: number}) {
    if (rowIndex === 1) {
      return 'warning-row'
    } else if (rowIndex === 3) {
      return 'success-row'
    }
    return ''
  }
}

</script>

<style lang="scss">
.el-row {
  margin-bottom: 20px;

  &:last-child {
    margin-bottom: 0;
  }
}

.el-col {
  border-radius: 4px;
}

.bg-purple-dark {
  background: #99a9bf;
}

.bg-purple {
  background: #d3dce6;
}

.bg-purple-light {
  background: #e5e9f2;
}

.grid-content {
  border-radius: 4px;
  min-height: 36px;
}

.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>
