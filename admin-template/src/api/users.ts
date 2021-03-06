import request from '@/utils/request'
import { ChangeUserType } from '@/typings'
import data from '@/views/pdf/content'
import { rejects } from 'assert'

export const getUsers = (params: any) =>
  request({
    url: '/users',
    method: 'get',
    params
  })

export const getUserInfo = (data: any) =>
  request({
    url: '/me',
    method: 'get',
    data
  })

export const getUserByName = (username: string) =>
  request({
    url: `/users/${username}`,
    method: 'get'
  })

export const updateUser = (username: string, data: any) =>
  request({
    url: `/users/${username}`,
    method: 'put',
    data
  })

export const deleteUser = (username: string) =>
  request({
    url: `/users/${username}`,
    method: 'delete'
  })

export const login = (data: any) =>
  request({
    url: '/authorizations',
    method: 'post',
    data
  })

export const logout = () =>
  request({
    url: '/users/logout',
    method: 'post'
  })

export const register = (data: any) =>
  request({
    url: '/users/register',
    method: 'post',
    data
  })

export const getUserList = (page: number) =>
  request({
    url: `/users?page=${page}`,
    method: 'get'
  })

export const updatePassword = (changeUserRow: ChangeUserType) => request({
  url: `/users/${changeUserRow.id}/password`,
  data: {
    password: changeUserRow.password
  },
  method: 'PUT'
})
