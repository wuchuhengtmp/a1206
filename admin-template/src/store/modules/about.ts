/**
 * The file is part of @wuchuhengtools/request
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/12
 * @listen MIT
 */
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators'
import store from '../index'
import { GetContent, UpdateContent } from '@/api/about'
import { getHash } from '@/utils/helper'

export interface AboutState {
  content: string
}

@Module({ dynamic: true, store, name: 'about' })
class About extends VuexModule implements AboutState {
  public content = ''

  @Mutation
  private _SET_CONTENT(content: string): void {
    this.content = content
  }

  @Action
  public async SetContent(content: string) {
    if ((await getHash(this.content)) !== (await getHash(content))) {
      this._SET_CONTENT(content)
    }
  }

  @Action
  public async getContent() {
    const { data } = await GetContent()
    this._SET_CONTENT(data.value)
  }

  /**
   * 更新到服务器那边
   */
  @Action
  public async save() {
    await UpdateContent(this.content)
  }
}

export const AboutModule = getModule(About)
