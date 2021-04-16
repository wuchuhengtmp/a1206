import { VuexModule, Module, Action, Mutation, getModule } from 'vuex-module-decorators'
import store from '@/store'
import type { ConfigSMSType, ConfigType, UserListPageType } from '@/typings'
import { getSMSConfig, updateSMSConfig } from '@/api/config'

@Module({ dynamic: true, store, name: 'config' })
class Config extends VuexModule implements ConfigType {
  public sms = {
    ALIYUN_SMS_TEMPLATE: '',
    ALIYUN_SMS_SIGN_NAME: '',
    ALIYUN_SMS_ACCESS_KEY_SECRET: '',
    ALIYUN_SMS_ACCESS_KEY_ID: ''
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
}

export const ConfigModule = getModule(Config)
