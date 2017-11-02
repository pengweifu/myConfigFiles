<?

/**
* 生成Resource类型的密钥，如果密钥文件内容被破坏，openssl_pkey_get_private函数返回false
*/
$pkeyStr='-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAx404P7bSR/E3OwMoSAzFNJ62KPJTp2bld13lZU8KU+PCXF07
jpFPW6nFxEFkIRxI4zjaMPRQVwrYtanRD7/G5klX0RsyafCrlztVStu8lsNaReI0
ItYJuDEzaHfaDCxAr+2ufNKtx0/z3Pt0dygQwCu9xH2kUoqhKLpFMjS4/RYmmp8R
DfIhqBTVwmTxXWWyx1WXZ0naSDs32CxsAPk+X1LR327ENKjQReHARu+OPMTzsXwr
w91cxZTh8fcvRwJ9gtyObwr+NcPcEPFkiovruxxw6CjCeiYGt6I0GvsUaPegoESs
GRots4H6JMcoQ/8yOWDvMedGTc90NRIwUiRjuwIDAQABAoIBAEiDecg1YC6CaiaI
nC+qbFMVxW4VuO5hBsuclEp/MWqQnkVAH+9LwIG3rMUHWiJqC9Z+B1O4xCUNLPi+
r9jAQL/YMRAsiOJUcZYwGmtsdBh4/qncbEOocbm8L0ksHFFGF2+WuExlpn6CAETy
Hz1ZktSyUHBBk9/mvm91WMXIwNw+dgwBODizDSRIXMVaoGccScAFYEiSFAuuGqro
kPUUEpORrBLJ6OGLsmcdwuCbVTsUyKp/yPsrlkN8eQo4lomsOnJcb6GJpWGgyKaa
cij4kPIK+fp7VZuNIwQ1ccf72Yq6S1wrG8xNVl3WZDEzfCqywqHuY05Jgu5GaMac
wvteLyECgYEA/V5SWdkrLBmMto4PSlEKvSr8aV2pH0aD+sRnRpa/APWRGUkAhLJV
OwVacAVDzSGF2v1w5RWrYjhltzlvfrdT0tyYSwgDJ06y8LHrZX4Ok4/WQ8mTJNab
QvVVqK9Gyq5HeMf8JR3f3T9pADUy7FgZ7eUVMcxbIoC+VLSyPFuOgDECgYEAyZ/O
IHSl37XLWU8FeXObm8uMVWRnWohEGL5iU8KOWRCTcrtzxCHB3OvSDhbXsHuzqsaM
xT7xOAm7Kb7zldXLEMj/6DmqJb9YRZO6MXLnP+lb3lpOMeiGRqm7O4wQdaVG3PKr
Gc4DicojrrnNeKpCfndOjbycHH5gbrmp/UVbM6sCgYBnRAU+d4PgEn3Gffn/aS5+
UUHVVAY1KBFsqJYLMC+jHXNJIfbjHqgcSR9GHu82cR5UVg2oYP+cJa5XUkJ+HAw/
Gge8NQTMBYhrD6kIkasxO2Ox9ztQvWNElCeYe+/XddbNhHzhR97RFyIdopEhwPq4
ejD8PfU7B8wLTCaE8TAQUQKBgQCxa+Rw/vSwBUNp1XWVyDuYPufAhNfF/L8fnvjb
iArHKST3Ayj94E693u4647LtZA1YK9vX/mk5zKZcN+IVacqFfLpkWcn16Yj9wN9J
gDkqLmlfIsxIcrsmF1nhLoGVZNkUuJ4D2RuX9r6rlGjQ09Irg0UmyF//Wt300YBa
lNuKfQKBgCeR1R/D/MWqZa+WTSTkvVe5ZWEDTBJ+YRQOstPwT/8LetlJGEowa4dO
KJV/b0btLaxOKqmW89d8VAricDPUuosdJfHi6f3ijrQkJ4Ztaf/iVjPVXelGgxWg
9RgqRgB83ESbqsEIFZmxxQCn2HUynD4/XFALoOGx7lFRORbB1RXM
-----END RSA PRIVATE KEY-----';
$privateKey = openssl_get_privatekey($pkeyStr);
/**
* 生成Resource类型的公钥，如果公钥文件内容被破坏，openssl_pkey_get_public函数返回false
*/
$okeyStr='-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx404P7bSR/E3OwMoSAzF
NJ62KPJTp2bld13lZU8KU+PCXF07jpFPW6nFxEFkIRxI4zjaMPRQVwrYtanRD7/G
5klX0RsyafCrlztVStu8lsNaReI0ItYJuDEzaHfaDCxAr+2ufNKtx0/z3Pt0dygQ
wCu9xH2kUoqhKLpFMjS4/RYmmp8RDfIhqBTVwmTxXWWyx1WXZ0naSDs32CxsAPk+
X1LR327ENKjQReHARu+OPMTzsXwrw91cxZTh8fcvRwJ9gtyObwr+NcPcEPFkiovr
uxxw6CjCeiYGt6I0GvsUaPegoESsGRots4H6JMcoQ/8yOWDvMedGTc90NRIwUiRj
uwIDAQAB
-----END PUBLIC KEY-----';
// $keyData = openssl_pkey_get_details($privateKey);
// $publicKey = openssl_get_publickey($keyData['key']);
$publicKey = openssl_get_publickey($okeyStr);
($privateKey && $publicKey) or die('密钥或者公钥不可用');
/**
* 原数据
*/
$originalData = '我的帐号是:shiki,密码是:matata';
/**
* 加密以后的数据，用于在网路上传输
*/
$encryptData = '';
echo '原数据为:', $originalData, PHP_EOL;
///////////////////////////////用私钥加密////////////////////////
if (openssl_private_encrypt($originalData, $encryptData, $privateKey)) {
    /**
     * 加密后 可以base64_encode后方便在网址中传输 或者打印  否则打印为乱码
     */
    echo '加密成功，加密后数据(base64_encode后)为:', base64_encode($encryptData), PHP_EOL;
} else {
    die('加密失败');
}
///////////////////////////////用公钥解密////////////////////////
/**
* 解密以后的数据
*/
$decryptData ='';
if (openssl_public_decrypt($encryptData, $decryptData, $publicKey)) {
    echo '解密成功，解密后数据为:', $decryptData, PHP_EOL;
} else {
    die('解密失败');
}
