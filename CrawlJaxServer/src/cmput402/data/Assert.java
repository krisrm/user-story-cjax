package cmput402.data;

import com.google.gson.Gson;

public class Assert {
	public String[] assertCalled;
	public AssertionFailed[] assertStack;
	public Long asserts;

	public String jsonErrors() {
		return assertStack == null || assertStack.length == 0 ? "{}" : new Gson().toJson(assertStack);
	}

//	public void parseErrors(Object o) {
//
//		for (Object err : (ArrayList<?>) o) {
//			AssertionFailed af = new AssertionFailed();
//			Map<?, ?> e = (Map<?, ?>) err;
//			//System.out.println(err + " " + err.getClass());
//			for (Object key : e.keySet()){
//				//System.out.println(key + " --- " + e.get(key));
//				if (key.toString().equals("id")){
//					af.id = e.get(key).toString();					
//				} else if ( key.toString().equals("error")){
//					af.error = e.get(key).toString();
//				}
//			}
//			assertStack.add(af);
//		}
//
//	}

	@Override
	public String toString() {
		return "Assert [assertStack=" + assertStack + ", asserts=" + asserts
				+ "]";
	}
}
