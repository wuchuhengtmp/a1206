/**
 * 设备分页数据
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/10
 * @listen MIT
 */

import { VuexModule, Module, Action, Mutation, getModule } from 'vuex-module-decorators'
import store from '../index'
import type {DeviceListPageType} from '@/typings'
import {getDeviceListPage} from "@/api/devices";

export interface DevicesState {
  deviceListPage: DeviceListPageType
}

@Module({ dynamic: true, store, name: 'device' })
class Devices extends VuexModule implements DevicesState {
  public deviceListPage: DeviceListPageType = { devices: [], total: 0, page: 1 }

  @Mutation
  private SET_DEVICE_LIST_PAGE(deviceListPage: DeviceListPageType): void {
    this.deviceListPage = {...this.deviceListPage, ...deviceListPage}
  }

  /**
   * 获取设备列表分页
   * @param page
   * @constructor
   */
  @Action
  public async GetDeviceListPage(page: number): Promise<void> {
    const { data } = await getDeviceListPage(page)
    this.SET_DEVICE_LIST_PAGE( { page, ...data })
  }
}

export const DevicesModule = getModule(Devices)

