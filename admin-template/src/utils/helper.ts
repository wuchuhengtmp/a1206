/**
 * The file is part of @wuchuhengtools/request
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/10
 * @listen MIT
 */

import { Loading as ElLoading, Message as ElMessage } from 'element-ui'
import { Component, Vue } from 'vue-property-decorator'

/**
 *  加载中装饰器
 * @constructor
 */
export const Loading = function() {
  return (target: Object, key: string, descriptor: PropertyDescriptor) => {
    const fn = descriptor.value
    descriptor.value = async function(...rest: any) {
      const loading = ElLoading.service({
        text: 'loading'
      })
      try {
        return await fn.call(this, ...rest)
      } catch (e) {

      } finally {
        loading.close()
      }
    }
  }
}

/**
 *  获取 hash串
 * @param str
 * @param algo
 */
export const getHash = (str: string, algo = 'SHA-256'): Promise<string> => {
  const strBuf = new TextEncoder().encode(str)
  return crypto.subtle.digest(algo, strBuf)
    .then(hash => {
      let result = ''
      const view = new DataView(hash)
      for (let i = 0; i < hash.byteLength; i += 4) {
        result += ('00000000' + view.getUint32(i).toString(16)).slice(-8)
      }
      return Promise.resolve(result)
    })
}

/**
 * 消息装饰器
 */
export const Message = () => {
  return (target: Vue, key: string, descriptor: PropertyDescriptor) => {
    const fn = descriptor.value
    descriptor.value = async function(...rest: any) {
      try {
        const res = fn(...rest)
        await ElMessage.success({ message: '操作成功' })
        return res
      } catch (e) {
        ElMessage.success({ message: '操作失败' })
      }
    }
  }
}

/**
 *  对象转请求链接参数
 *
 * @param obj
 */
export const obj2Query = (obj: Record<string, number | string>): string => {
  let res = ''
  for (const e in obj) {
    if (obj[e] !== '') {
      res += `${e}=${obj[e]}&`
    }
  }
  if (res === '') return ''
  res = res.substr(0, res.length - 1)
  return '?' + res
}

/**
 * query 转obj
 * @param query
 */
export const query2Obj = (query: string): Record<string, string> => {
  const res: Record<string, string> = {}
  if (query.length === 0) {
    return res
  }
  query = query.substr(1, query.length - 1)

  for (const e of query.split('&')) {
    const [k, v] = e.split('=')
    res[k] = v
  }
  return res
}
