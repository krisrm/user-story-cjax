package cmput402;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;

public class HTTPUtil {

	public static HttpResponse doPost(String url, List<NameValuePair> nameValuePairs){
		HttpClient httpclient = new DefaultHttpClient();
	    HttpPost httppost = new HttpPost(url);
	    try {
	        httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
	        
	        return httpclient.execute(httppost);
	    } catch (ClientProtocolException e) {
	    	e.printStackTrace();
	    } catch (IOException e) {
	    	e.printStackTrace();
	    }
	    return null;
	}
	
	public static HttpResponse postCallback(String url, int id, String session, Long asserts, String jsonError){
		 List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(4);
	     nameValuePairs.add(new BasicNameValuePair("id", Integer.toString(id)));
	     nameValuePairs.add(new BasicNameValuePair("sessid", session));
	     nameValuePairs.add(new BasicNameValuePair("errors", jsonError));
	     nameValuePairs.add(new BasicNameValuePair("asserts", Long.toString(asserts)));
	     return doPost(url,nameValuePairs);
	}
	
	public static String readResponse(HttpResponse r){
		String body = "";
		try {
			BufferedReader br = new BufferedReader(new InputStreamReader(r.getEntity().getContent()));
			String line = br.readLine();
			while (line != null){
				body += line;
				line = br.readLine();
			}
		} catch (IllegalStateException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return body;
	}

	public static HttpResponse postFinished(String callback, String session) {
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(4);
		nameValuePairs.add(new BasicNameValuePair("finished", "true"));
		nameValuePairs.add(new BasicNameValuePair("sessid", session));
		return doPost(callback,nameValuePairs);
		
	}
	
}
