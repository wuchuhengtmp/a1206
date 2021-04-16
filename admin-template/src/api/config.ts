import request from '@/utils/request'
import { ChangeUserType, ConfigSMSType } from '@/typings'
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
