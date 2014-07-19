# Description: main processing file for migration data
# encoding: utf-8
import sys, os
from Config import CConfig
from TransportData import CTransportData
from Utility import Utilities
import time
from inspect import stack

#Class MigrationThread proccess migration data
class CMigrationThread(object):
	def __init__(self):
		# Create config object
		self._oConfig = CConfig
		# Create SystemHealthController
		self._oTransportData = CTransportData()

	#Run threading
	def Migration(self):
		try:
			timeStart = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
			self._oTransportData.LoadAllConnection()
			self._oTransportData.MigrationData()
			timeEnd = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
			Utilities.WriteLogTracking(timeStart, timeEnd)
		except Exception, exc:
			strErrorMsg = '%s.%s Error: %s - Line: %s' % (self.__class__.__name__, str(exc), stack()[0][3], sys.exc_traceback.tb_lineno) # give a error message
			Utilities.WriteErrorLog(strErrorMsg, self._oConfig)

if __name__ == '__main__':
	nStartTime = time.time()
	oConfig    = CConfig()
	try:
		oMigrationThread = CMigrationThread()
		oMigrationThread.Migration()
	except Exception, exc:
		strErrorMsg = '%s Error: %s - Line: %s' % (str(exc), stack()[0][3], sys.exc_traceback.tb_lineno) # give a error message
		Utilities.WriteErrorLog(strErrorMsg, oConfig)

	nEndTime = time.time()
	nDuration = nEndTime - nStartTime
	print 'Duration:%s' % nDuration
	Utilities.TransferDataTrapper2Zabbix("migration_monitor_assistant", nDuration, oConfig)