import { VuexModule, Module, Action, Mutation, getModule } from 'vuex-module-decorators'
import store from '@/store'
import type { ConfigQiniuType, ConfigSMSType, ConfigType, UserListPageType } from '@/typings'
import { getQiniuConfig, getSMSConfig, updateQiniuConfig, updateSMSConfig } from '@/api/config'

@Module({ dynamic: true, store, name: 'config' })
class Config extends VuexModule implements ConfigType {
  public sms = {
    ALIYUN_SMS_TEMPLATE: '',
    ALIYUN_SMS_SIGN_NAME: '',
    ALIYUN_SMS_ACCESS_KEY_SECRET: '',
    ALIYUN_SMS_ACCESS_KEY_ID: ''
  }

  public qiniu = {
    QINIU_ACCESSKEY: '',
    QINIU_BUCKET: '',
    QINIU_DOMAIN: '',
    QINIU_SECRETKEY: ''
  }

  @Mutation
  private SET_QINIU(qiniu: ConfigQiniuType) {
    this.qiniu = qiniu
  }

  @Mutation
  private SET_SMS(sms: ConfigSMSType) {
    this.sms = sms
  }

  @Action
  public async getSmsConfig() {
    const { data } = await getSMSConfig()
    this.SET_SMS(data)
  }

  @Action
  public setSms(newSms: ConfigSMSType) {
    this.SET_SMS(newSms)
  }

  @Action
  public async updateSmsConfig() {
    await updateSMSConfig(this.sms)
  }

  @Action
  public async getQiniuConfig() {
    const { data } = await getQiniuConfig()
    this.SET_QINIU(data)
  }

  @Action
  public setQiniu(newQiniu: ConfigQiniuType) {
    this.SET_QINIU(newQiniu)
  }

  @Action
  public async updateQiniuConfig() {
    await updateQiniuConfig(this.qiniu)
  }
}

export const ConfigModule = getModule(Config)
