<template>
  <div>
    hello
  </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
const promiseMiddleware = (middlewares: any[], ctx: any) => {
  const promise = Promise.resolve(null)
  let next
  let onion = (next: Promise.resolve<any>) => next // 声明洋葱最里层

  // 1. 通过bind把执行上下文对象，绑定到中间件第一个参数
  middlewares.forEach((fn, i) => {
    // middlewares[i] = fn.bind(null, ctx)
    // 把中间件由内到外一层层加入洋葱
    onion = fn.bind(null, Promise.resolve(onion))
  })
  return onion(Promise.resolve(ctx))

  // // 2. 通过while循环执行promise实例
  // while ((next = middlewares.shift())) {
  //   promise = promise.then(next)
  // }
  //
  // // 3. 最终返回一个promise实例结果
  // return promise.then(() => {
  //   return 'test'
  // })
}

@Component({
  name: 'tmp'
})
export default class extends Vue {
  mounted() {
    // const fn1 = (next: Promise.resolve<any>) => {
    //   console.log(this.name)
    //   return next()
    // }
    // const fn2 = (next: any) => {
    //   console.log(this.name)
    //   return next
    // }
    // const fns = [fn1, fn2]
    // const onion = (fn) => fn()
    // fns.forEach((fn, i) => {
    //
    // })

    let onlion = () => {
      const p = 'hello'
      console.log(p)
      return p
    }
    const fn1 = (next) => {
      console.log('fn1')
      return next()
    }
    const fn2 = (next) => {
      console.log('fn2')
      return next()
    }
    const fns = [fn1, fn2]
    fns.reverse().forEach(fn => {
      onlion = fn.bind(null, onlion)
    })
    onlion()
    debugger

    // promiseMiddleware([
    //   fn1, fn2
    // ], 'hello').then(res => {
    //   console.log(1)
    //   console.log(res)
    //   console.log(2)
    // })
  }
}
</script>
