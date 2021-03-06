/**
 * The file is part of @wuchuhengtools/request
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/10
 * @listen MIT
 */

declare type UserIdType = number
declare type UserPasswordType = string
export type UserType = {
  id: UserIdType
  createdAt: string
  nickname: string
  username: string
  avatar: string
  totalDevice: number
}

export type ChangeUserType = UserType & {password: UserPasswordType}

/**
 * 用户列表分页
 */
export type UserListPageType = {
  users: UserType[]
  total: number
  page: number
}

declare type DeviceType = {
  id: number //
  deviceId: string // 设备ID
  ipAddress: string // 设备ip
  status: 'online' | 'offline' // online 或 offline
  playState: 0 | 1 | 3 // 播放状态定义：系统上电处于停止状态00(停止)01(播放)02(暂停)
  playMode: number // (00)按顺序播放全盘曲目,播放完后循环播放单曲循环 (01)一直循环播放当前曲目单曲停止 (02)播放完当前曲目一次停止全盘随机(03)随机播放盘符内曲目目录循环 (04)按顺序播放当前文件夹内曲目,播放完后循环播放 (05)在当前目录内随机播放，目录不包含子目录目录顺序播放 (06)按顺序播放当前文件夹内曲目,播放完后停止 (07)按顺序播放全盘曲目,播放完后停止\n
  playSound: number // 音量
  alias: string //  别名
  categoryName: string // 分类名
  fileCnt: number // 总曲目数
  fileCurrent: string // 当前曲目
  memorySize: number // 内存余量
  createdAt: string // 创建时间
  categoryId: number // 分类id
}

declare type DeviceListPageType = {
  devices: DeviceType[]
  total: number
  page: number
}

/**
 * 设备查询参数枚举类型
 */
declare type DeviceQueryType = {
  page: string
  username: string
  status: string
}

type DashboardItemType = {
  list: number[]
  total: number
}
declare type DashboardType = {
  user: DashboardItemType
  userForWeek: DashboardItemType
  onlineDevices: DashboardItemType
  devices: DashboardItemType
}

declare type ConfigSMSType = {
  ALIYUN_SMS_TEMPLATE: string
  ALIYUN_SMS_SIGN_NAME: string
  ALIYUN_SMS_ACCESS_KEY_SECRET: string
  ALIYUN_SMS_ACCESS_KEY_ID: string
}
declare type ConfigQiniuType = {
  QINIU_ACCESSKEY: string
  QINIU_BUCKET: string
  QINIU_DOMAIN: string
  QINIU_SECRETKEY: string
}

declare type ConfigType = {
  sms: ConfigSMSType
  qiniu: ConfigQiniuType
}
