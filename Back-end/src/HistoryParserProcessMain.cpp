#include "LogParser/HistoryLogParser.h"
#include "Common/DBCommon.h"
//------------------------------------
#include <ctime>
#include <stdio.h>
#include <stdlib.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <errno.h>
#include <unistd.h>
#include <pthread.h>
#include <dirent.h>

//------------------------------------
extern "C"
{
   #include <pthread.h>
}
#include <iostream>
using namespace std;
//using namespace mongo;
#define ListConfig "ConfigList"

void* Parse(void* strCfgFile);
pid_t CheckProc(const char* name);
bool CheckDestroy(string strCfgFile);

ofstream BugLogFile;
int main(int argc, char* argv[])
{
	int child_id;
	string strLine;
	ifstream ListCfgFile;

	if(CheckProc("HistoryParserProcess") != -1)
	{
		printf ("Process is existed !!\n");
		return 0;
	}

	BugLogFile.open(paraLogFile, ios::out | ios::app );
	ListCfgFile.open(ListConfig);

	printf ("Create Host Parsing Process : %s !!\n", argv[0]);
	
	if (ListCfgFile.is_open())
	{
		while(!ListCfgFile.eof())
		{
			getline(ListCfgFile,strLine);
			remove(strLine.begin(),strLine.end(),'\n');
			cout << strLine.c_str() << endl;
			child_id = fork();
			if (child_id) {
				cout << "I'm parent of " << child_id << endl;
			}
			else {	
				//cout << "Log folder " << strLine.c_str() << endl;
				CHistoryLogParser *pHistoryParser = new CHistoryLogParser(strLine.c_str());
				pHistoryParser->ProcessParse();
				delete pHistoryParser;
			}
		}

		ListCfgFile.close();
	}
	
	return 0;
}

bool CheckDestroy(string strCfgFile)
{
	ifstream fConfig;
	string strLine;
	fConfig.open(strCfgFile.c_str());
	if(fConfig.is_open())
	{
		getline(fConfig,strLine);
		cout << strLine.c_str() << endl;
		if(strLine.compare("true") != 0)
		{
			BugLogFile<<"Destroy !! /n";
			fConfig.close();
			return false;
		}
	}
	return true;
}

pid_t CheckProc(const char* name) 
{
	DIR* dir;
    struct dirent* ent;
    char buf[512];

    long  pid;
    char pname[100] = {0,};
    char state;
    FILE *fp=NULL; 
	int nCount = 0;

    if (!(dir = opendir("/proc"))) {
        perror("can't open /proc");
        return -1;
    }

    while((ent = readdir(dir)) != NULL) {
        long lpid = atol(ent->d_name);
        if(lpid < 0)
            continue;
        snprintf(buf, sizeof(buf), "/proc/%ld/stat", lpid);
        fp = fopen(buf, "r");

        if (fp) {
            if ( (fscanf(fp, "%ld (%[^)]) %c", &pid, pname, &state)) != 3 ){
                printf("fscanf failed \n");
                fclose(fp);
                closedir(dir);
                return -1; 
            }
            if (!strcmp(pname, name)) {
				if(nCount == 0)
				{
					nCount = 1;
				}
				else
				{
                fclose(fp);
                closedir(dir);
				return (pid_t)lpid;
				}
            }
            fclose(fp);
        }
    }

	closedir(dir);
	return -1;
}