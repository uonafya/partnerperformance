import pandas as pd
from mysql.connector import connect

PARAMS = {}

# DB settings for source of targets and achieved
db_host = "143.198.232.18"
db_pass = "@Microsoft2010@@"
db_user = "hcm"
db_name = "hcm"
# Target DB settings for updating targets
db_host_tgt = "127.0.0.1"
db_pass_tgt = "Pass$2022"
# ===============================================================
# Syntax set at 3 don't change backlog setting without logic change
backlog_mon = 3
partner_ids = '92, 88, 95, 48, 99, 100, 96, 98, 94'
# partner_ids = '94'

# Floating target query
ft_query = '''SELECT * FROM facility_floating_target where
partner_id in ({partner_ids}) and financial_year = {fy}
and month = {ref_mon}
'''

tgt_query = '''select partner_id, ptn.name as Partner,
vft.county as county_id, vft.countyname as County, 1 as month,
financial_year, facility_id, vft.name as facility,
round((tx_new_below_15_female + tx_new_below_15_male +
tx_new_above_15_female + tx_new_above_15_male)/12) as target,
null as Achieved, null as deficit, null as floating_target
from t_facility_hfr_target
inner join partners ptn on ptn.id=partner_id
inner join view_facilitys vft on vft.id=facility_id
where partner_id in ({partner_ids}) and financial_year = {fy}
'''

# Achievements query - ALL
ach_query = '''select (SUM(tx_new_above_15_female) +
SUM(tx_new_above_15_male) + SUM(tx_new_below_15_female) +
SUM(tx_new_below_15_male)) AS tx_new, year as financial_year,
month, p.name as partner,
p.id as partner_id, view_facilitys.countyname as county,
view_facilitys.county as county_id,
view_facilitys.id as facility_id
from d_hfr_submission
inner join view_facilitys on view_facilitys.id = d_hfr_submission.facility
inner join supported_facilities sf on sf.facility_id = view_facilitys.id
inner join partners p on sf.partner_id = p.id
inner join weeks on weeks.id = d_hfr_submission.week_id where
partner_id in ({partner_ids}) and month in ({curr_mons}) and
p.funding_agency_id=1 and financial_year= {fy}
group by year, month, p.name, county, county_id, partner_id,
facility_id
order by year asc, month asc'''

# Upsert Query for floating targets
ups_query = '''INSERT INTO facility_floating_target
    (
        partner_id, Partner, county_id, county, `month`, financial_year,
        target, Achieved, deficit, floating_target, facility_id, facility
    )
VALUES
    ({vals})
ON DUPLICATE KEY UPDATE
    {vals_check};
'''

# Values for posting
val = "{partner_id}, '{Partner}', {county_id}, '{County}', {month}, "
val += "{financial_year}, {target}, {Achieved}, {deficit}, "
val += "{floating_target}, {facility_id}, '{facility}'"

# Values for checking
val_check = 'partner_id = {partner_id}, county_id = {county_id}, '
val_check += 'month = {month}, financial_year = {financial_year}, '
val_check += 'facility_id = {facility_id}'

# Unique key
ukey = '{financial_year}{month}{partner_id}{county_id}{facility_id}'

PARAMS['partner_ids'] = partner_ids


def get_months(curr_mon, lts=False):
    """ Method to get list of months
       Allowed values 1 to 12
    """
    try:
        min_mon = curr_mon - backlog_mon
        start_mon = 1 if min_mon < 1 else min_mon
        mons = []
        for i in range(start_mon, curr_mon + 1):
            mons.append(i)
        fmon = ','.join(list(map(str, mons)))
    except Exception as e:
        raise e
    else:
        if lts:
            return mons
        return fmon


def get_ref_ft(fy, ref_mon):
    """Method to get reference floating target."""
    try:
        ref_fts = []
        PARAMS['fy'] = fy
        PARAMS['ref_mon'] = ref_mon
        query = tgt_query.format(**PARAMS)
        with connect(
            host=db_host, user=db_user,
            password=db_pass, database=db_name,
        ) as connection:
            # Achievements
            with connection.cursor() as cursor:
                cursor.execute(query)
                result = cursor.fetchall()
                acolumns = [i[0] for i in cursor.description]
                for row in result:
                    dts = list(row)
                    ref_fts.append(dts)
                df = pd.DataFrame(ref_fts, columns=acolumns)
    except Exception as e:
        raise e
    else:
        df.to_csv('RefTargets.csv', index=False)
        return df


def create_df(pre_df, df, mon):
    """Method to slice dataframe for a give month."""
    try:
        ndf = df.loc[df['month'] == mon]
        join_cols = ['partner_id', 'county_id',
                     'financial_year', 'target', 'facility_id']
        merge_cols = ['deficit'] + join_cols

        mdf = pd.merge(ndf, pre_df[merge_cols], how='inner', on=join_cols)
        mdf['floating_target'] = mdf['target'] + mdf['deficit_y']
        mdf['deficit'] = mdf['deficit_x']
        mdf.drop(['deficit_y'], axis=1)
        # mdf.to_csv('Mons_%s.csv' % mon, index=False)
    except Exception as e:
        raise e
    else:
        return mdf


def calc_targets(fy, curr_mon):
    try:
        curr_mons = get_months(curr_mon)
        targets = []
        achievs = []
        PARAMS['fy'] = fy
        PARAMS['curr_mons'] = curr_mons
        query1 = tgt_query.format(**PARAMS)
        query2 = ach_query.format(**PARAMS)
        try:
            with connect(
                host=db_host, user=db_user,
                password=db_pass, database=db_name,
            ) as connection:
                # Get Targets
                with connection.cursor() as cursor:
                    cursor.execute(query1)
                    result = cursor.fetchall()
                    tcolumns = [i[0] for i in cursor.description]
                    for row in result:
                        for i in range(1, 13):
                            dts = list(row)
                            dts[4] = i
                            if i == 1:
                                dts[11] = dts[8]
                            targets.append(dts)
                    df = pd.DataFrame(targets, columns=tcolumns)

                # Achievements
                with connection.cursor() as cursor:
                    cursor.execute(query2)
                    result = cursor.fetchall()
                    acolumns = [i[0] for i in cursor.description]
                    for row in result:
                        dts = list(row)
                        achievs.append(dts)
                    df1 = pd.DataFrame(achievs, columns=acolumns)
        except Exception as e:
            raise e
            print(e)

        # df.to_csv('Targets.csv', index=False)
        # df1.to_csv('Achieved.csv', index=False)

        join_cols = ['partner_id', 'county_id', 'financial_year',
                     'month', 'facility_id']
        merge_cols = ['tx_new'] + join_cols
        ndf = df.merge(df1[merge_cols], how='inner', on=join_cols)
        ndf['Achieved'] = ndf['tx_new']
        ndf['deficit'] = ndf['target'] - ndf['tx_new']

        ndf.sort_values(by=['partner_id', 'month'])

        # Take first month as the point of reference then go back 3 Mon ONLY
        all_dfs = []
        mon_back1 = curr_mon - 1 if curr_mon > 1 else 0
        mon_back2 = curr_mon - 2 if curr_mon > 2 else 0
        mon_back3 = curr_mon - 3 if curr_mon > 3 else 0
        mon0 = 1 if curr_mon < 4 else (curr_mon - 3)

        print('REF POINT Start Month : ', mon0)
        if mon0 > 1:
            # Get last floating point from DB i.e >3 month data not editable
            # m0 = pd.DataFrame()
            m0 = get_ref_ft(fy, mon0)
        else:
            m0 = ndf.loc[ndf['month'] == mon0]
            all_dfs.append(m0)

        if m0.empty:
            print('Reference point FT Must exist or start from Month <= 4')
            return False

        if mon_back1:
            m1 = create_df(m0, ndf, curr_mon - 2)
            all_dfs.append(m1)
        if mon_back2:
            m2 = create_df(m1, ndf, curr_mon - 1)
            all_dfs.append(m2)
        if mon_back3:
            m3 = create_df(m2, ndf, curr_mon)
            all_dfs.append(m3)

        rdf = pd.concat(all_dfs, ignore_index=True, sort=False)
        rdf['deficit'] = rdf['target'] - rdf['tx_new']
        if curr_mon > 1:
            rdf.drop(['deficit_y'], axis=1)

        # print(rdf.columns)
        rdf = rdf.fillna(0)
        rdf.to_csv('Floating-Targets.csv', index=False)
        # Create SQL statements
        tlns = 0
        upsert_query = []
        cols = list(rdf.columns.values)
        trecords = len(rdf.index)
        print('Total records', trecords)
        dicts = rdf.to_dict('dict')
        for tr in range(0, trecords):
            prms = {}
            for col in cols:
                prms[col] = dicts[col][tr]
            vl_check = val_check.format(**prms)
            vl = val.format(**prms)
            u_key = ukey.format(**prms)
            uprms = {'vals': vl, 'vals_check': vl_check}
            fquery = ups_query.format(**uprms)
            upsert_query.append(fquery)
            print('*' * 100)
            # unique key not used as it turned out too long
            print(u_key)
            ulen = len(u_key)
            tlns = ulen if ulen > tlns else tlns
            # print('ulen', tlns)
            print(fquery)
        # Update Floating targets
        '''
        with connect(
            host=db_host, user="hcm",
            password=db_pass, database="hcm",
        ) as connection:
            with connection.cursor() as cursor:
                for qry in upsert_query:
                    cursor.execute(qry)
                connection.commit()
        '''
    except Exception as e:
        raise e
    else:
        pass


if __name__ == '__main__':
    fy = 2022
    curr_mon = 4
    print('Start Floating targets calculation')
    print('For FY %d and Month %d' % (fy, curr_mon))
    print('-' * 100)
    calc_targets(fy, curr_mon)
    print('*' * 100)
    print('Done Calculating saved to Floating-Targets.csv')
