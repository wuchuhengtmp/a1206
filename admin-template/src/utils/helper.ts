/**
 * The file is part of @wuchuhengtools/request
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/10
 * @listen MIT
 */

import { Loading as ElLoading } from 'element-ui'

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
