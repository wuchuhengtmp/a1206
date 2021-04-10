import request from '@/utils/request'

export const getDeviceListPage = (page: number) =>
  request({
    url: `/devices?page=${page}`,
    method: 'get',
  })
