import request from '@/utils/request'
import { DeviceQueryType } from '@/typings'
import { obj2Query } from '@/utils/helper'

export const getDeviceListPage = (page: DeviceQueryType) =>
  request({
    url: `/devices${obj2Query(page)}`,
    method: 'get'
  })
