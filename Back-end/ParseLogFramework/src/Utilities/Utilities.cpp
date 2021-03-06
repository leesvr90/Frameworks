#include "Utilities.h"
#include "../Config/Config.h"
#include "../Config/ConfigReader.h"


/*
 * Constructor - Assign a file name
 */
CUtilities::CUtilities()
{

}

/*
 * Destructor -
 */
CUtilities::~CUtilities()
{

}

void CUtilities::WriteErrorLog(CConfig* oConfig, const std::string& strErrorMsg)
{
	ofstream fErrorLog;
	string strErrorLog;
	strErrorLog = oConfig->GetErrorLog();
	fErrorLog.open(strErrorLog.c_str());
	fErrorLog << strErrorMsg << "\n";
	fErrorLog.close();
}

void CUtilities::WriteErrorLog(const std::string& strErrorMsg)
{	
	string strErrorLog;
	CConfigReader* oConfigReader = new CConfigReader("./SyncConfig.ini");
	strErrorLog = oConfigReader->load("ERROR", "ErrorLog");
	ofstream fErrorLog;
	fErrorLog.open(strErrorLog.c_str(),std::ofstream::app);
	fErrorLog << strErrorMsg << "\n";
	fErrorLog.close();
	delete oConfigReader;
}

string CUtilities::FormatDateSuffixHistory(struct tm tm)
{
	stringstream ssDatetimeSuffix;
	string strSuffix;
	
	ssDatetimeSuffix << tm.tm_year + 1900;
	if(tm.tm_mon < 9)
		ssDatetimeSuffix << '0';
	ssDatetimeSuffix << tm.tm_mon + 1;
	if(tm.tm_mday < 10)
		ssDatetimeSuffix << '0';
	ssDatetimeSuffix << tm.tm_mday;

	ssDatetimeSuffix << "_";

	if(tm.tm_hour < 10)
		ssDatetimeSuffix << '0';
	ssDatetimeSuffix << tm.tm_hour;
	
	if(tm.tm_min < 10)
		ssDatetimeSuffix << '0';
	ssDatetimeSuffix << tm.tm_min;
	
	strSuffix = ssDatetimeSuffix.str();
	return strSuffix;
}

string CUtilities::GetDateSuffixHistory(int iPeriod)
{
	stringstream ssDatetimeSuffix;
	string strSuffix;
	int iCurrentMinute, iModMinute, iFileMinute;

	time_t t = time(NULL);
	struct tm tm = *localtime(&t);

	iModMinute		= tm.tm_min % iPeriod;
	iFileMinute		= tm.tm_min - iModMinute;

	tm.tm_min = tm.tm_min - iModMinute;
	strSuffix = FormatDateSuffixHistory(tm);
	
	return strSuffix;
}



std::vector<int> CUtilities::GetListZabbixProcessId(string strPathDatePattern) {
	string strCommand = "ls " + strPathDatePattern + "*";
	std::vector<int> vtProcessId;
	int iProcessId; 
	string ls = GetStdoutFromCommand(strCommand);
    typedef vector< string > split_vector_type;
	if(ls.find("cannot access") != std::string::npos)
		return vtProcessId;
    split_vector_type SplitVec; 
    boost::split( SplitVec, ls, boost::is_any_of("\n"), boost::token_compress_on ); 
    std::vector<int>::size_type sz = SplitVec.size();
	
	
    for (unsigned i=0; i<sz; i++)
	{
		string strFileName = SplitVec[i];
		
		if ( strFileName != "" ) 
		{
			 split_vector_type vecTemp; // #2: Search for tokens
			 boost::split( vecTemp, strFileName, boost::is_any_of("_"), boost::token_compress_on );
			 std::vector<int>::size_type iVecTempSize = vecTemp.size();
			 iProcessId = atoi(vecTemp[iVecTempSize - 1 ].c_str());
			 vtProcessId.push_back(iProcessId);
		}
	}

	return vtProcessId;
}

	
string CUtilities::GetStdoutFromCommand(string cmd) {
  string data;
  FILE * stream;
  const int max_buffer = 256;
  char buffer[max_buffer];
  cmd.append(" 2>&1");

  stream = popen(cmd.c_str(), "r");
  if (stream) {
	while (!feof(stream))
        if (fgets(buffer, max_buffer, stream) != NULL) data.append(buffer);
        pclose(stream);
    }
  return data;
}

vector<string> CUtilities::SplitString(string strBuffer, string strSplit)
{
	//{lo-127.0.0.1,00:00:00:00:00:00;eth0-103.23.156.13,00:50:56:B5:5C:FA;eth1-172.16.97.5,00:50:56:B5:5C:FB}
	int iFind;
	vector<string> vRes;
	string strTmp;
	while(true)
	{
		iFind = strBuffer.find(strSplit.c_str());
		if(iFind == std::string::npos)
		{
			strTmp = strBuffer.substr(0,strBuffer.length());
			vRes.push_back(strTmp);
			break;
		}
		strTmp = strBuffer.substr(0,iFind);
		strBuffer = strBuffer.substr(iFind+1);
		vRes.push_back(strTmp);
	}
	return vRes;
}

string CUtilities::ReplaceString(string subject, const string& search,
                          const string& replace) {
    size_t pos = 0;
    while ((pos = subject.find(search, pos)) != std::string::npos) {
         subject.replace(pos, search.length(), replace);
         pos += replace.length();
    }
    return subject;
}

string CUtilities::VIMJsonParser(string VInfo)
{
	map<string,string> vimRes;
	int iIDPos, iKeyPos;
	iIDPos = VInfo.find("vimid");
	iKeyPos = VInfo.find("vimkey");
	if(iIDPos == std::string::npos || iKeyPos == std::string::npos)
	{
		return "NULL";
	}
	vimRes["vimid"] = VInfo.substr(iIDPos+8,iKeyPos-3-(iIDPos+8));
	vimRes["vimkey"] = VInfo.substr(iKeyPos+9,VInfo.find("}") - 1 - (iKeyPos+9));
	
	return vimRes["vimkey"];
}

string CUtilities::GetMacAddressCorrectly(string strInterfaceInfo)
{
	const char* pattern =
        "(([0-9A-Fa-f]{2}[-:_]){5}[0-9A-Fa-f]{2})|(([0-9A-Fa-f]{4}\\.){2}[0-9A-Fa-f]{4})";
    boost::regex ip_regex(pattern);

    boost::sregex_iterator it(strInterfaceInfo.begin(), strInterfaceInfo.end(), ip_regex);
    boost::sregex_iterator end;
	if(it == end)
		return "";
    return it->str();
}

vector<string> CUtilities::GetIPAddressCorrectly(string strInterfaceInfo)
{
	vector<string> vResult;
    const char* pattern =
        "\\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)"
        "\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)"
        "\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)"
        "\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\b";
    boost::regex ip_regex(pattern);

    boost::sregex_iterator it(strInterfaceInfo.begin(), strInterfaceInfo.end(), ip_regex);
    boost::sregex_iterator end;
	if(it == end)
		return vResult;
    for (; it != end; ++it) {
        vResult.push_back(it->str());   
    }
	return vResult;
}

unsigned long CUtilities::IpToLong(string ip){
	//char* ipadr = strIp.c_str();
	unsigned long num=0,val;
	char ipadr[ip.length()];
	char *tok,*ptr;
	strcpy(ipadr,ip.c_str());
	tok=strtok(ipadr,".");
	while( tok != NULL)
	{
		val=strtoul(tok,&ptr,0);
		num=(num << 8) + val;
		tok=strtok(NULL,".");
	}
	delete[] tok;
	tok = 0;
	
	return num;
}

string CUtilities::RemoveBraces(string strWithBraces) {

	if(strWithBraces.size() < 2 || strWithBraces.compare("EOO") == 0 || strWithBraces.compare("null") == 0)
		return strWithBraces;
	strWithBraces = strWithBraces.substr(1,strWithBraces.size()-2);
	return strWithBraces;
}

string CUtilities::ToLowerString(string strText) {

	for(int i = 0; strText[i] != '\0'; i++){
		strText[i] = tolower(strText[i]);
	}
	return strText;
}

string CUtilities::ToUpperString(string strText) {

	for(int i = 0; strText[i] != '\0'; i++){
		strText[i] = toupper(strText[i]);
	}
	return strText;
}

string CUtilities::GetMongoObjId(string strObjId) {
	strObjId = strObjId.substr(8);
	strObjId = CUtilities::RemoveBraces(strObjId);
	strObjId = CUtilities::RemoveBraces(strObjId);
	return strObjId;
}

string CUtilities::GetCurrTime()
{
	string strRes;
	stringstream strCurrTime;
	time_t t = time(NULL);
	struct tm tm = *localtime(&t);
	//========get current day time=======
	strCurrTime << tm.tm_year + 1900 << "-";
	strCurrTime << tm.tm_mon + 1 << "-";
	strCurrTime << tm.tm_mday << " ";
	strCurrTime << tm.tm_hour << ":";
	strCurrTime << tm.tm_min << ":";
	strCurrTime << tm.tm_sec;
	//================================
	strRes = strCurrTime.str();
	return strRes;
}

string CUtilities::GetCurrTimeStamp()
{
	string strRes;
	stringstream strCurrTimeStamp;
	std::time_t t = std::time(0);  // t is an integer type
	strCurrTimeStamp << t;
	strRes = strCurrTimeStamp.str();
    return strRes;
}

string CUtilities::GetNameByWebKey(string strKey)
{
	int iFindStart, iFindEnd;
	string strRes;
	iFindStart = strKey.find("[");
	iFindEnd = strKey.find(",");
	strRes = "";
	if(iFindStart != std::string::npos)
	{
		if(iFindEnd != std::string::npos)
			strRes = strKey.substr(iFindStart+1,iFindEnd - iFindStart - 1);
		else
		{
			iFindEnd = strKey.find("]");
			if(iFindEnd != std::string::npos)
				strRes = strKey.substr(iFindStart+1,iFindEnd - iFindStart - 1);
		}
	}
	return strRes;
	
}

string CUtilities::GetStepNameByWebKey(string strKey)
{
	int iFindStart, iFindEnd;
	string strRes;
	iFindStart = strKey.find(",");
	iFindEnd = strKey.find(",",iFindStart+1);
	strRes = "";
	if(iFindStart != std::string::npos)
	{
		if(iFindEnd != std::string::npos)
			strRes = strKey.substr(iFindStart+1,iFindEnd - iFindStart - 1);
		else
		{
			iFindEnd = strKey.find("]");
			if(iFindEnd != std::string::npos)
				strRes = strKey.substr(iFindStart+1,iFindEnd - iFindStart - 1);
		}
	}
	return strRes;
	
}

string CUtilities::GetUnitByWebKey(string strKey)
{
	int iFindStart, iFindEnd;
	string strRes;
	iFindStart = strKey.find_last_of(",");
	iFindEnd = strKey.find("]",iFindStart+1);
	strRes = "";
	if(iFindStart != std::string::npos)
	{
		if(iFindEnd != std::string::npos)
			strRes = strKey.substr(iFindStart+1,iFindEnd - iFindStart - 1);
	}
	return strRes;
	
}

string CUtilities::ReplaceBlockBracket(string strBlockValue)
{
	string strValue;
	strValue = strBlockValue;
	strValue = CUtilities::ReplaceString(strValue, "{", "");
	strValue = CUtilities::ReplaceString(strValue, "}", "");
	//strValue = strValue.replace(strValue.begin(), strValue.end(), '{', '');
	//strValue = strValue.replace(strValue.begin(), strValue.end(), '}', '');
	return strValue;
}

string CUtilities::GetSuffixPartition(long long lClock, int iPartitionDay)
{
	stringstream strSuffix;
	time_t t = (time_t)lClock;
	struct tm tm = *localtime(&t);
	//========get current day timetime=======
	strSuffix << tm.tm_year + 1900;
	if(tm.tm_mon < 9)
		strSuffix << '0';
	strSuffix << tm.tm_mon + 1;
	// if(tm.tm_mday < iPartitionDay*2 + 1)
		// strSuffix << '0';
	// if(tm.tm_mday % iPartitionDay >= 1)
		// strSuffix << ((tm.tm_mday/iPartitionDay)*iPartitionDay + 1);
	// else
		// strSuffix << (tm.tm_mday - (iPartitionDay - 1));
	//================================
	return strSuffix.str();
}

string CUtilities::FormatLog(string strType, string strProcessName ,string strFunctionName, string strInfo)
{
	string strLog;
	string strDateTime;
	strDateTime = CUtilities::GetCurrTime();
	//[DATETIME] [TYPE][PROCESS NAME] [FUNCTION NAME] [INFO] (thêm [FILENAME | LINE] 
	strLog = "["+ strDateTime +"]" + "["+ strType +"]" + "["+ strProcessName +"]" + "["+ strFunctionName +"]" + "["+ strInfo +"]";
	return strLog;
}