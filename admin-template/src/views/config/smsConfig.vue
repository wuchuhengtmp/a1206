<template>
  <div class="app-container">
    <el-row :gutter="30">
      <el-col :span="24">
        <el-tabs v-model="activeName" @tab-click="handleClick">
          <el-tab-pane label="阿里云短信配置" name="sms" type="inline">
            <el-row :gutter="30" type="flex" align="center">
              <el-col :span="12" :offset="6">
                <el-form ref="smsForm" :model="sms" label-width="15rem">
                  <el-form-item label="access_key" >
                    <el-input v-model="sms.ALIYUN_SMS_ACCESS_KEY_ID"/>
                  </el-form-item>
                  <el-form-item label="access_key_secret" >
                    <el-input v-model="sms.ALIYUN_SMS_ACCESS_KEY_SECRET" />
                  </el-form-item>
                  <el-form-item label="签名" >
                    <el-input v-model="sms.ALIYUN_SMS_SIGN_NAME" />
                  </el-form-item>
                  <el-form-item label="模板" >
                    <el-input v-model="sms.ALIYUN_SMS_TEMPLATE" />
                  </el-form-item>
                  <el-form-item>
                    <el-button type="primary" @click="handleSmsSave">保存</el-button>
                  </el-form-item>
                </el-form>
              </el-col>
            </el-row>
          </el-tab-pane>
          <el-tab-pane label="Task" name="fourth">Task</el-tab-pane>
        </el-tabs>
      </el-col>
    </el-row>
  </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import { ConfigModule } from '@/store/modules/Config'
import { ConfigSMSType } from '@/typings'
import { Loading } from '@/utils/helper'

@Component({
  name: 'smsConfig'
})
export default class extends Vue {
  activeName = 'sms'

  get sms() {
    return ConfigModule.sms
  }

  set sms(newSms: ConfigSMSType) {
    return ConfigModule.setSms(newSms)
  }

  handleClick(tab: any) {
    console.log(tab)
  }

  mounted() {
    ConfigModule.getSmsConfig()
  }

  @Loading
  public handleSmsSave() {
    this.sms = { ...this.sms }
    try {
      ConfigModule.updateSmsConfig()
      this.$message.success({ message: '操作成功' })
    } catch (e) {
      this.$message.error({ message: '操作失败' })
    }
  }
}
</script>

<style scoped>

</style>
