import request from '@/utils/request'
import { ChangeUserType, ConfigQiniuType, ConfigSMSType } from '@/typings'
import data from '@/views/pdf/content'
import { rejects } from 'assert'

export const getSMSConfig = () =>
  request({
    url: '/configs/sms',
    method: 'get'
  })

export const updateSMSConfig = (data: ConfigSMSType) =>
  request({
    url: '/configs/sms',
    method: 'put',
    data
  })

export const getQiniuConfig = () =>
  request({
    url: '/configs/qiniu',
    method: 'get'
  })

export const updateQiniuConfig = (data: ConfigQiniuType) =>
  request({
    url: '/configs/qiniu',
    method: 'put',
    data
  })
