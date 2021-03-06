#include <iostream>
#include <fstream>
#include <stdio.h>
#include <stdlib.h>

#include <boost/algorithm/string.hpp>
#include <boost/algorithm/string/split.hpp>
#include <iterator> // for ostream_iterator
#include <vector>
#include <map>
#include <boost/regex.hpp>
#include <string>

using namespace std;


#ifndef UTILITIES_H
#define UTILITIES_H

class CConfig;

class CUtilities
{
	private:
		string m_strErrorFile;

	public: 
		CUtilities();
		virtual ~CUtilities();
		
		static void WriteErrorLog(CConfig* oConfig, const std::string& strErrorMsg);
		static void WriteErrorLog(const std::string& strErrorMsg);
		static string FormatDateSuffixHistory(struct tm tm);
		static string GetDateSuffixHistory(int iPeriod);
		static std::vector<int> GetListZabbixProcessId(string strPathDatePattern);
		static string GetStdoutFromCommand(string cmd);
		static string ReplaceString(string subject, const string& search, const string& replace);
		static string VIMJsonParser(string VInfo);
		static unsigned long IpToLong(string strIp);
		static vector<string> SplitString(string strBuffer, string strSplit);
		static vector<string> GetIPAddressCorrectly(string strInterfaceInfo);
		static string GetMacAddressCorrectly(string strInterfaceInfo);
		static string RemoveBraces(string strWithBraces);
		static string GetMongoObjId(string strObjId);
		static string GetCurrTime();
		static string GetCurrTimeStamp();
		static string ToLowerString(string strText);
		static string ToUpperString(string strText);
		static string GetNameByWebKey(string strKey);
		static string GetStepNameByWebKey(string strKey);
		static string GetUnitByWebKey(string strKey);
		static string ReplaceBlockBracket(string strBlockValue);
		static string GetSuffixPartition(long long lClock, int iPartitionDay);
		static string FormatLog(string strType, string strProcessName, string strFunctionName, string strInfo);
};

#endif //UTILITIES_H