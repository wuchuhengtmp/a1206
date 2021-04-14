<template>
  <div class="dashboard-editor-container">
<!--    <github-corner class="github-corner" />-->

    <panel-group @handle-set-line-chart-data="handleSetLineChartData" />

    <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
      <users-line-chart :chart-data="dashboard.user.list" v-if="activeItem === 'user'"/>
      <user-week-line-chart :chart-data="dashboard.userForWeek.list" v-if="activeItem === 'userForWeek'"/>
      <devices-week-line-chart :chart-data="dashboard.onlineDevices.list" v-if="activeItem === 'onlineDevices'"/>
      <devices-line-chart :chart-data="dashboard.devices.list" v-if="activeItem === 'devices'"/>
    </el-row>

<!--    <el-row :gutter="32">-->
<!--      <el-col-->
<!--        :xs="24"-->
<!--        :sm="24"-->
<!--        :lg="8"-->
<!--      >-->
<!--        <div class="chart-wrapper">-->
<!--          <radar-chart />-->
<!--        </div>-->
<!--      </el-col>-->
<!--      <el-col-->
<!--        :xs="24"-->
<!--        :sm="24"-->
<!--        :lg="8"-->
<!--      >-->
<!--        <div class="chart-wrapper">-->
<!--          <pie-chart />-->
<!--        </div>-->
<!--      </el-col>-->
<!--      <el-col-->
<!--        :xs="24"-->
<!--        :sm="24"-->
<!--        :lg="8"-->
<!--      >-->
<!--        <div class="chart-wrapper">-->
<!--          <bar-chart />-->
<!--        </div>-->
<!--      </el-col>-->
<!--    </el-row>-->

<!--    <el-row :gutter="8">-->
<!--      <el-col-->
<!--        :xs="{span: 24}"-->
<!--        :sm="{span: 24}"-->
<!--        :md="{span: 24}"-->
<!--        :lg="{span: 12}"-->
<!--        :xl="{span: 12}"-->
<!--        style="padding-right:8px;margin-bottom:30px;"-->
<!--      >-->
<!--        <transaction-table />-->
<!--      </el-col>-->
<!--      <el-col-->
<!--        :xs="{span: 24}"-->
<!--        :sm="{span: 12}"-->
<!--        :md="{span: 12}"-->
<!--        :lg="{span: 6}"-->
<!--        :xl="{span: 6}"-->
<!--        style="margin-bottom:30px;"-->
<!--      >-->
<!--      </el-col>-->
<!--      <el-col-->
<!--        :xs="{span: 24}"-->
<!--        :sm="{span: 12}"-->
<!--        :md="{span: 12}"-->
<!--        :lg="{span: 6}"-->
<!--        :xl="{span: 6}"-->
<!--        style="margin-bottom:30px;"-->
<!--      >-->
<!--        <box-card />-->
<!--      </el-col>-->
<!--    </el-row>-->

  </div>
</template>

<script lang="ts">
import 'echarts/theme/macarons.js' // Theme used in BarChart, LineChart, PieChart and RadarChart
import { Component, Vue } from 'vue-property-decorator'
import GithubCorner from '@/components/GithubCorner/index.vue'
import BarChart from './components/BarChart.vue'
import BoxCard from './components/BoxCard.vue'
import LineChart, { ILineChartData } from './components/LineChart.vue'
import PanelGroup from './components/PanelGroup.vue'
import PieChart from './components/PieChart.vue'
import RadarChart from './components/RadarChart.vue'
import TodoList from './components/TodoList/index.vue'
import TransactionTable from './components/TransactionTable.vue'
import type { DashboardType } from '@/typings'
import { DashboardModule } from '@/store/modules/dashboard'
import DevicesLineChart from '@/views/dashboard/admin/components/DevicesLineChart.vue'
import UserWeekLineChart from '@/views/dashboard/admin/components/UserWeekLineChart.vue'
import UsersLineChart from '@/views/dashboard/admin/components/usersLineChart.vue'
import DevicesWeekLineChart from '@/views/dashboard/admin/components/DevicesWeekLineChart.vue'

@Component({
  name: 'DashboardAdmin',
  components: {
    DevicesWeekLineChart,
    UsersLineChart,
    UserWeekLineChart,
    DevicesLineChart,
    GithubCorner,
    BarChart,
    BoxCard,
    LineChart,
    PanelGroup,
    PieChart,
    RadarChart,
    TodoList,
    TransactionTable
  }
})
export default class extends Vue {
  private activeItem: keyof DashboardType = 'user'

  private handleSetLineChartData(type: keyof DashboardType) {
    this.activeItem = type
  }

  get dashboard(): DashboardType {
    return DashboardModule.dashboard
  }
}
</script>

<style lang="scss" scoped>
.dashboard-editor-container {
  padding: 32px;
  background-color: rgb(240, 242, 245);
  position: relative;

  .github-corner {
    position: absolute;
    top: 0px;
    border: 0;
    right: 0;
  }

  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}

@media (max-width:1024px) {
  .chart-wrapper {
    padding: 8px;
  }
}
</style>
