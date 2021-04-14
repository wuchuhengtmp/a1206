<template>
  <div
    :class="className"
    :style="{height: height, width: width}"
  />
</template>

<script lang="ts">
import * as echarts from 'echarts'
import { Component, Prop, Watch } from 'vue-property-decorator'
import { mixins } from 'vue-class-component'
import ResizeMixin from '@/components/Charts/mixins/resize'

export type ILineChartData = number[]

@Component({
  name: 'UsersLineChart'
})
export default class extends mixins(ResizeMixin) {
  @Prop({ required: true }) private chartData!: ILineChartData
  @Prop({ default: 'chart' }) private className!: string
  @Prop({ default: '100%' }) private width!: string
  @Prop({ default: '350px' }) private height!: string

  @Watch('chartData', { deep: true })
  private onChartDataChange(value: ILineChartData) {
    this.setOptions(value)
  }

  mounted() {
    this.$nextTick(() => {
      this.initChart()
    })
  }

  beforeDestroy() {
    if (!this.chart) {
      return
    }
    this.chart.dispose()
    this.chart = null
  }

  private initChart() {
    this.chart = echarts.init(this.$el as HTMLDivElement, 'macarons')
    this.setOptions(this.chartData)
  }

  private setOptions(chartData: ILineChartData) {
    let c = Date.now()
    let days: string[] = []
    for (let i = 1; i <= 30; i++) {
      const d = new Date(c)
      days.push(`${d.getMonth() + 1}.${d.getDate()}`)
      c -= 60 * 60 * 24 * 1000
    }
    days = days.reverse()

    if (this.chart) {
      this.chart.setOption({
        xAxis: {
          data: days,
          boundaryGap: false,
          axisTick: {
            show: false
          }
        },
        grid: {
          left: 10,
          right: 10,
          bottom: 20,
          top: 30,
          containLabel: true
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross'
          },
          padding: 8
        },
        yAxis: {
          axisTick: {
            show: false
          }
        },
        legend: {
          data: ['用户总数']
        },
        series: [
          {
            name: '用户总数',
            smooth: true,
            type: 'line',
            itemStyle: {
              color: '#40c9c6',
              lineStyle: {
                color: '#3888fa',
                width: 2
              },
              areaStyle: {
                color: '#f3f8ff'
              }
            },
            data: chartData,
            animationDuration: 2800,
            animationEasing: 'quadraticOut'
          }
        ]
      })
    }
  }
}
</script>
