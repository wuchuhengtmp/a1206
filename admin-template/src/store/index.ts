import Vue from 'vue'
import Vuex from 'vuex'
import { IAppState } from './modules/app'
import { IUserState } from './modules/user'
import { ITagsViewState } from './modules/tags-view'
import { IErrorLogState } from './modules/error-log'
import { IPermissionState } from './modules/permission'
import { ISettingsState } from './modules/settings'
import { DevicesState } from './modules/deviceListPage'
import { AboutState } from '@/store/modules/about'
import { DashboardState } from '@/store/modules/dashboard'

Vue.use(Vuex)

export interface IRootState {
  app: IAppState
  user: IUserState
  tagsView: ITagsViewState
  errorLog: IErrorLogState
  permission: IPermissionState
  settings: ISettingsState
  devices: DevicesState
  about: AboutState
  dashboard: DashboardState
}

// Declare empty store first, dynamically register all modules later.
export default new Vuex.Store<IRootState>({})
