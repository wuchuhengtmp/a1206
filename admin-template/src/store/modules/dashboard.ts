/**
 * The file is part of @wuchuhengtools/request
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/12
 * @listen MIT
 */
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators'
import store from '../index'
import { DashboardType } from '@/typings'
import { GetDashboard } from '@/api/dashboard'

export interface DashboardState {
  dashboard: DashboardType
}

@Module({ dynamic: true, store, name: 'dashboard' })
class Dashboard extends VuexModule implements DashboardState {
  public dashboard: DashboardType = {
    user: { total: 0, list: [] },
    userForWeek: { total: 0, list: [] },
    onlineDevices: { total: 0, list: [] },
    devices: { total: 0, list: [] }
  }

  @Mutation
  private _SET_DASHBOARD(dashboard: DashboardType): void {
    this.dashboard = dashboard
  }

  @Action
  public async getDashboard() {
    const { data } = await GetDashboard()
    this._SET_DASHBOARD(data)
  }
}

export const DashboardModule = getModule(Dashboard)
